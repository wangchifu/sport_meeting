<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Action;
use App\Item;
use App\StudentSign;
use ZipArchive;

class SchoolScoreController extends Controller
{
    public function score_input($action_id=null)
    {
        $actions = Action::where('code',auth()->user()->code)->orderBy('id','DESC')->get();
        $action_array = [];
        foreach($actions as $action){
            $action_array[$action->id] = $action->name;
        }
        $select_action = null;
        if(empty($action_id)){
            $select_action = key($action_array);
        }else{
            $select_action = $action_id;
        }

        $items = Item::where('action_id',$select_action)
            ->where('code',auth()->user()->code)
            ->where('disable',null)
            ->orderBy('order')
            ->get();


        $data = [
            'action_array'=>$action_array,
            'select_action'=>$select_action,
            'items'=>$items,
        ];

        return view('school_scores.score_input',$data);
    }

    public function score_input_do(Action $action,Item $item)
    {
        $student_signs = StudentSign::where('item_id',$item->id)
            ->orderBy('student_year')
            ->orderBy('student_class')
            ->orderBy('sex')
            ->get();

        foreach($student_signs as $student_sign){
            $student_array[$student_sign->student_year][$student_sign->sex][$student_sign->id]['id'] = $student_sign->id;
            $student_array[$student_sign->student_year][$student_sign->sex][$student_sign->id]['number'] = $student_sign->student->number;
            $student_array[$student_sign->student_year][$student_sign->sex][$student_sign->id]['name'] = $student_sign->student->name;
            $student_array[$student_sign->student_year][$student_sign->sex][$student_sign->id]['achievement'] = $student_sign->achievement;
            $student_array[$student_sign->student_year][$student_sign->sex][$student_sign->id]['ranking'] = $student_sign->ranking;
        }

        $data = [
            'action'=>$action,
            'item'=>$item,
            'student_signs'=>$student_signs,
            'student_array'=>$student_array,
        ];

        return view('school_scores.score_input_do',$data);

    }

    public function score_input_update(Request  $request)
    {
        $checkbox = $request->input('checkbox');
        $achievement = $request->input('achievement');
        $ranking = $request->input('ranking');
        $action_id = $request->input('action_id');
        $item_id = $request->input('item_id');
        $item = Item::find($item_id);
        //??????
        if($item->type==1){
            //?????????
            asort($achievement);
        }
        //??????
        if($item->type==2){
            //?????????
            arsort($achievement);
        }
        $r=1;
        foreach($achievement as $k=>$v){
            $student_sign = StudentSign::find($k);
            $att['achievement'] = $achievement[$k];
            if($checkbox =="on" and $item->type <> 3){
                $att['ranking'] = $r;
            }else{
                $att['ranking'] = $ranking[$k];
            }
            $student_sign->update($att);
            $r++;
        }

        return redirect()->route('school_scores.score_input_do',['action'=>$action_id,'item'=>$item_id]);
    }


    public function score_print($action_id=null)
    {


        return view('school_scores.score_print');
    }

    public function score_input_print(Action $action,Item $item,$year,$sex)
    {
        $student_signs = StudentSign::where('action_id',$action->id)
            ->where('item_id',$item->id)
            ->where('student_year',$year)
            ->where('sex',$sex)
            ->orderBy('ranking')
            ->get();

        $odt_folder = storage_path('app/public').'/'.auth()->user()->code;

        $zip = new ZipArchive;
        if(file_exists($odt_folder.'/'.$year.'??????'.$sex.'??????'.$item->name.'??????.odt')){
            unlink($odt_folder.'/'.$year.'??????'.$sex.'??????'.$item->name.'??????.odt');
        }

        if ($zip->open($odt_folder.'/'.$year.'??????'.$sex.'??????'.$item->name.'??????.odt', ZipArchive::CREATE) === TRUE) {
            $zip->addFile($odt_folder . '/demo/manifest.rdf', 'manifest.rdf');
            $zip->addFile($odt_folder . '/demo/meta.xml', 'meta.xml');
            $zip->addFile($odt_folder . '/demo/settings.xml', 'settings.xml');

            $content = $odt_folder . '/demo/content.xml';
            if (file_exists($content)) {
                $fp = fopen($content, "r");
                $str = fread($fp, filesize($content));//????????????????????????????????????????????????????????????

                $d = explode('<office:body>',$str);
                $odt_head = $d[0];
                $a = explode('</office:body>',$d[1]);
                $odt_body = $a[0];
                $odt_foot = $a[1];

                $data_body = null;
                foreach($student_signs as $student_sign){
                    if(!empty($student_sign->achievement)){
                        $this_student = $student_sign->student->name;
                        $action_name = str_replace('??????','',$action->name);
                        $group = $sex."??????";
                        $item_name = $item->name;
                        $ranking = $student_sign->ranking;
                        $score = $student_sign->achievement;
                        $print_date = "????????????".date('Y').'???'.date('m').'???'.date('d').'???';

                        //??????
                        $str2 = str_replace("{{????????????}}", $this_student, $odt_body);
                        $str2 = str_replace("{{???????????????}}", $action_name, $str2);
                        $str2 = str_replace("{{??????}}", $group, $str2);
                        $str2 = str_replace("{{??????}}", $item_name, $str2);
                        $str2 = str_replace("{{??????}}", $ranking, $str2);
                        $str2 = str_replace("{{??????}}", $score, $str2);
                        $str2 = str_replace("{{??????}}", $print_date, $str2);

                        $data_body .= $str2;
                    }
                }

                $odt = $odt_head."<office:body>".$data_body."</office:body>".$odt_foot;

                //dd($str);

                //?????? content2.xml
                //?????????
                if(file_exists($odt_folder . '/demo/content2.xml')){
                    unlink($odt_folder . '/demo/content2.xml');
                }
                $fp2 = fopen($odt_folder . '/demo/content2.xml', "a+"); //????????????
                fwrite($fp2, $odt);
                fclose($fp2);

                $zip->addFile($odt_folder . '/demo/content2.xml', 'content.xml');
                $zip->addFile($odt_folder . '/demo/mimetype', 'mimetype');
                $zip->addFile($odt_folder . '/demo/styles.xml', 'styles.xml');
                $zip->addFile($odt_folder . '/demo/META-INF/manifest.xml', 'META-INF/manifest.xml');


                $zip->close();
            }

            return response()->download($odt_folder.'/'.$year.'??????'.$sex.'??????'.$item->name.'??????.odt');
        }


    }

    public function print_extra(Request $request)
    {
        $odt_folder = storage_path('app/public').'/'.auth()->user()->code;

        $zip = new ZipArchive;
        if(file_exists($odt_folder.'/????????????.odt')){
            unlink($odt_folder.'/????????????.odt');
        }
        if ($zip->open($odt_folder.'/????????????.odt', ZipArchive::CREATE) === TRUE) {
            $zip->addFile($odt_folder . '/demo/manifest.rdf', 'manifest.rdf');
            $zip->addFile($odt_folder . '/demo/meta.xml', 'meta.xml');
            $zip->addFile($odt_folder . '/demo/settings.xml', 'settings.xml');

            $content = $odt_folder . '/demo/content.xml';
            if (file_exists($content)) {
                $fp = fopen($content, "r");
                $str = fread($fp, filesize($content));//????????????????????????????????????????????????????????????
                $this_student = $request->input('this_student');
                $action_name = $request->input('action_name');
                $group = $request->input('group');
                $item = $request->input('item');
                $ranking = $request->input('ranking');
                $score = $request->input('score');
                $print_date = $request->input('print_date');

                //??????
                $str = str_replace("{{????????????}}", $this_student, $str);
                $str = str_replace("{{???????????????}}", $action_name, $str);
                $str = str_replace("{{??????}}", $group, $str);
                $str = str_replace("{{??????}}", $item, $str);
                $str = str_replace("{{??????}}", $ranking, $str);
                $str = str_replace("{{??????}}", $score, $str);
                $str = str_replace("{{??????}}", $print_date, $str);

                //?????? content2.xml
                //?????????
                if(file_exists($odt_folder . '/demo/content2.xml')){
                    unlink($odt_folder . '/demo/content2.xml');
                }
                $fp2 = fopen($odt_folder . '/demo/content2.xml', "a+"); //????????????
                fwrite($fp2, $str);
                fclose($fp2);

                $zip->addFile($odt_folder . '/demo/content2.xml', 'content.xml');
                $zip->addFile($odt_folder . '/demo/mimetype', 'mimetype');
                $zip->addFile($odt_folder . '/demo/styles.xml', 'styles.xml');
                $zip->addFile($odt_folder . '/demo/META-INF/manifest.xml', 'META-INF/manifest.xml');


                $zip->close();
            }

            return response()->download($odt_folder . '/????????????.odt');
        }

    }

    public function demo_upload(Request $request)
    {
        $school_code = auth()->user()->code;
        $folder = 'public/'. $school_code ;
        //??????????????????
        if ($request->hasFile('demo')) {
            $demo = $request->file('demo');
            $info = [
                'original_filename' => $demo->getClientOriginalName(),
                'extension' => $demo->getClientOriginalExtension(),
            ];

            $demo->storeAs($folder, 'demo.odt');
        }

        $odt_folder = storage_path('app/public').'/'.auth()->user()->code;

        $zip = new ZipArchive;
        $res = $zip->open($odt_folder.'/demo.odt');
        if ($res === TRUE) {
            $zip->extractTo($odt_folder.'/demo');
            $zip->close();
        }


        return redirect()->route('school_scores.score_print');
    }
}
