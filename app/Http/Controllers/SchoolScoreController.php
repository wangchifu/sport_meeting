<?php

namespace App\Http\Controllers;

use App\StudentClass;
use Illuminate\Http\Request;
use App\Action;
use App\Item;
use App\StudentSign;
use ZipArchive;
use Rap2hpoutre\FastExcel\FastExcel;

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

    public function score_input_do(Request $request)
    {
        $action = Action::find($request->input('action_id'));
        $item = Item::find($request->input('item_id'));
        $student_signs = StudentSign::where('item_id',$item->id)
            ->where('sex',$request->input('sex'))
            ->where('student_year',$request->input('student_year'))
            ->orderBy('order')
            ->orderBy('student_class')
            ->orderBy('is_official','DESC')
            ->get();

        $student_array = [];
        foreach($student_signs as $student_sign){
            if($item->game_type=="personal"){
                $student_array[$student_sign->id]['id'] = $student_sign->id;
                $student_array[$student_sign->id]['number'] = $student_sign->student->number;
                $student_array[$student_sign->id]['name'] = $student_sign->student->name;
                $student_array[$student_sign->id]['achievement'] = $student_sign->achievement;
                $student_array[$student_sign->id]['ranking'] = $student_sign->ranking;
                $student_array[$student_sign->id]['order'] = $student_sign->order;
            }
            if($item->game_type=="group"){
                    $student_array[$student_sign->student_year.$student_sign->student_class.$student_sign->group_num][$student_sign->id]['id'] = $student_sign->id;
                    $student_array[$student_sign->student_year.$student_sign->student_class.$student_sign->group_num][$student_sign->id]['number'] = $student_sign->student->number;
                    $student_array[$student_sign->student_year.$student_sign->student_class.$student_sign->group_num][$student_sign->id]['name'] = $student_sign->student->name;
                    $student_array[$student_sign->student_year.$student_sign->student_class.$student_sign->group_num][$student_sign->id]['achievement'] = $student_sign->achievement;
                    $student_array[$student_sign->student_year.$student_sign->student_class.$student_sign->group_num][$student_sign->id]['ranking'] = $student_sign->ranking;
                    $student_array[$student_sign->student_year.$student_sign->student_class.$student_sign->group_num][$student_sign->id]['order'] = $student_sign->order;
                    $student_array[$student_sign->student_year.$student_sign->student_class.$student_sign->group_num][$student_sign->id]['is_official'] = $student_sign->is_official;
            }
        }

        if($item->game_type=="class") {

            $cht_num = config('chcschool.cht_num');
            foreach ($student_signs as $student_sign) {
                $class_name = $cht_num[$student_sign->student_year] . "年" . $student_sign->student_class . "班";
                $student_array[$student_sign->id]['name'] = $class_name;
                $student_array[$student_sign->id]['student_year'] = $student_sign->student_year;
                $student_array[$student_sign->id]['student_class'] = $student_sign->student_class;
                $student_array[$student_sign->id]['achievement'] = $student_sign->achievement;
                $student_array[$student_sign->id]['ranking'] = $student_sign->ranking;
                $student_array[$student_sign->id]['order'] = $student_sign->order;

            }
        }
        $data = [
            'year'=>$request->input('student_year'),
            'sex'=>$request->input('sex'),
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
        $order = $request->input('order');
        $action_id = $request->input('action_id');
        $item_id = $request->input('item_id');
        $item = Item::find($item_id);
        //徑賽
        if($item->type==1){
            //小到大
            asort($achievement);
        }
        //田賽
        if($item->type==2){
            //大到小
            arsort($achievement);
        }
        $r=1;
        foreach($achievement as $k=>$v){

            $att['achievement'] = $achievement[$k];
            if($checkbox =="on" and $item->type <> 3){
                $att['ranking'] = $r;
            }else{
                $att['ranking'] = $ranking[$k];
            }

            $att['order'] = (isset($order[$k]))?$order[$k]:null;

            $student_sign = StudentSign::find($k);
            $student_sign->update($att);
            if($item->game_type=="group"){
                $all_student_signs = StudentSign::where('item_id',$item->id)
                    ->where('student_year',$student_sign->student_year)
                    ->where('student_class',$student_sign->student_class)
                    ->where('group_num',$student_sign->group_num)
                    ->where('sex',$student_sign->sex)
                    ->update($att);
            }

            $r++;
        }

        return redirect()->route('school_scores.score_input_do',['action_id'=>$action_id,'item_id'=>$item_id,'student_year'=>$student_sign->student_year,'sex'=>$student_sign->sex]);
    }


    public function score_print($action_id=null)
    {
        return view('school_scores.score_print');
    }

    public function score_input_print(Action $action,Item $item,$year,$sex)
    {
        $cht_num = config('chcschool.cht_num');
        $student_signs = StudentSign::where('action_id',$action->id)
            ->where('item_id',$item->id)
            ->where('student_year',$year)
            ->where('sex',$sex)
            ->where('ranking','<>',null)
            ->orderBy('ranking')
            ->orderBy('is_official','DESC')
            ->get();

        $odt_folder = storage_path('app/public').'/'.auth()->user()->code;

        $zip = new ZipArchive;
        if(file_exists($odt_folder.'/'.$year.'年級'.$sex.'子組'.$item->name.'獎狀.odt')){
            unlink($odt_folder.'/'.$year.'年級'.$sex.'子組'.$item->name.'獎狀.odt');
        }

        if ($zip->open($odt_folder.'/'.$year.'年級'.$sex.'子組'.$item->name.'獎狀.odt', ZipArchive::CREATE) === TRUE) {
            $zip->addFile($odt_folder . '/demo/manifest.rdf', 'manifest.rdf');
            $zip->addFile($odt_folder . '/demo/meta.xml', 'meta.xml');
            $zip->addFile($odt_folder . '/demo/settings.xml', 'settings.xml');

            $content = $odt_folder . '/demo/content.xml';
            if (file_exists($content)) {
                $fp = fopen($content, "r");
                $str = fread($fp, filesize($content));//指定讀取大小，這裡把整個檔案內容讀取出來

                $d = explode('<office:body>',$str);
                $odt_head = $d[0];
                $a = explode('</office:body>',$d[1]);
                $odt_body = $a[0];
                $odt_foot = $a[1];

                $data_body = null;
                $y = date('Y') - 1911;

                $score_data = [];
                $group_student = [];
                $i = 0;
                $item_name = $item->name;
                $print_date = "中華民國".$y.'年'.date('m').'月'.date('d').'日';
                $print_date_c = date('Y').'年'.date('m').'月'.date('d').'日';
                //取代
                $action_name = str_replace('報名','',$action->name);

                $first = $student_signs->first();
                $last_group =$first->student_year.$first->student_class.$first->group_num;
                foreach($student_signs as $student_sign) {
                    //if(!empty($student_sign->achievement)){
                        if($student_sign->student_year=="幼小" or $student_sign->student_year=="幼中" or $student_sign->student_year=="幼大"){
                            $score_data[$i]['year_name'] = $cht_num[$student_sign->student_year];
                            $score_data[$i]['year_class_name'] = $cht_num[$student_sign->student_year].$student_sign->student_class . "班";
                            $score_data[$i]['cht_year_class_name'] = $cht_num[$student_sign->student_year] . $cht_num[$student_sign->student_class] . "班";
                        }else{
                            $score_data[$i]['year_name'] = $cht_num[$student_sign->student_year] . "年級";
                            $score_data[$i]['year_class_name'] = $cht_num[$student_sign->student_year] . "年" . $student_sign->student_class . "班";
                            $score_data[$i]['cht_year_class_name'] = $cht_num[$student_sign->student_year] . "年" . $cht_num[$student_sign->student_class] . "班";
                        }
                        $score_data[$i]['class_name'] = $student_sign->student_class . "班";
                        $score_data[$i]['cht_class_name'] = $cht_num[$student_sign->student_class] . "班";

                        if($item->game_type=="personal"){
                            $score_data[$i]['ranking'] = $student_sign->ranking;
                            $score_data[$i]['achievement'] = $student_sign->achievement;

                            $score_data[$i]['this_student'] = $student_sign->student->name;
                            $score_data[$i]['group'] = ($sex=="4")?"不分性別組":$sex."子組";
                            $i++;
                        }
                        if($item->game_type=="group"){
                            $class_group = $student_sign->student_year.$student_sign->student_class.$student_sign->group_num;
                            if($last_group <> $class_group){
                                $i++;
                            }
                            $score_data[$i]['ranking'] = $student_sign->ranking;
                            $score_data[$i]['achievement'] = $student_sign->achievement;
                            $score_data[$i]['class_name'] = $cht_num[$student_sign->student_year] . "年" . $student_sign->student_class . "班";
                            $score_data[$i]['cht_class_name'] = $cht_num[$student_sign->student_year] . "年" . $cht_num[$student_sign->student_class] . "班";

                            if(!isset($group_student[$i])) $group_student[$i] = "";
                            if($student_sign->is_official == null){
                                $st_name = $student_sign->student->name."(候)";
                            }else{
                                $st_name = $student_sign->student->name;
                            }
                            $group_student[$i] .= $st_name." ";
                            $score_data[$i]['group'] = ($sex=="4")?"不分性別組":$sex."子組";
                            $last_group = $class_group;
                        }
                        if($item->game_type=="class"){
                            $score_data[$i]['ranking'] = $student_sign->ranking;
                            $score_data[$i]['achievement'] = $student_sign->achievement;
                            $score_data[$i]['this_student'] = "";
                            $score_data[$i]['group'] = "班際賽";
                            $i++;
                        }
                    //}
                }

                $i=0;
                foreach($score_data as $k=>$v){
                    if($i<$item->reward){
                        if($item->game_type=="group"){
                            $str2 = str_replace("{{年班同學}}", $v['year_class_name']."</text:p><text:p text:style-name=\"P12\">".$group_student[$k], $odt_body);
                            $str2 = str_replace("{{姓名}}", $group_student[$k], $str2);
                        }else{
                            $str2 = str_replace("{{年班同學}}", $v['year_class_name']." ".$v['this_student'], $odt_body);
                            $str2 = str_replace("{{姓名}}", $v['this_student'], $str2);
                        }
                        $str2 = str_replace("{{年班}}", $v['year_class_name'], $str2);
                        $str2 = str_replace("{{國字年班}}", $v['cht_year_class_name'], $str2);
                        $str2 = str_replace("{{年級}}", $v['year_name'], $str2);
                        $str2 = str_replace("{{班別}}", $v['class_name'], $str2);
                        $str2 = str_replace("{{國字班別}}", $v['cht_class_name'], $str2);
                        $str2 = str_replace("{{運動會名稱}}", $action_name, $str2);
                        $str2 = str_replace("{{組別}}", $v['group'], $str2);
                        $str2 = str_replace("{{項目}}", $item_name, $str2);
                        $str2 = str_replace("{{名次}}", "第".$v['ranking']."名", $str2);
                        if(isset($cht_num[$v['ranking']])){
                            $str2 = str_replace("{{國字名次}}", "第".$cht_num[$v['ranking']]."名", $str2);
                        }
                        $str2 = str_replace("{{成績}}", $v['achievement'], $str2);
                        $str2 = str_replace("{{日期}}", $print_date, $str2);
                        $str2 = str_replace("{{西元日期}}", $print_date_c, $str2);

                        $data_body .= $str2;
                        $i++;
                    }
                }

                $odt = $odt_head."<office:body>".$data_body."</office:body>".$odt_foot;

                //dd($str);

                //寫入 content2.xml
                //先刪除
                if(file_exists($odt_folder . '/demo/content2.xml')){
                    unlink($odt_folder . '/demo/content2.xml');
                }
                $fp2 = fopen($odt_folder . '/demo/content2.xml', "a+"); //開啟檔案
                fwrite($fp2, $odt);
                fclose($fp2);

                $zip->addFile($odt_folder . '/demo/content2.xml', 'content.xml');
                $zip->addFile($odt_folder . '/demo/mimetype', 'mimetype');
                $zip->addFile($odt_folder . '/demo/styles.xml', 'styles.xml');
                $zip->addFile($odt_folder . '/demo/META-INF/manifest.xml', 'META-INF/manifest.xml');


                $zip->close();
            }

            return response()->download($odt_folder.'/'.$year.'年級'.$sex.'子組'.$item->name.'獎狀.odt');
        }


    }

    //團體賽印個人獎狀
    public function score_input_print2(Action $action,Item $item,$year,$sex)
    {
        $cht_num = config('chcschool.cht_num');
        $student_signs = StudentSign::where('action_id',$action->id)
            ->where('item_id',$item->id)
            ->where('student_year',$year)
            ->where('sex',$sex)
            ->orderBy('ranking')
            ->orderBy('is_official','DESC')
            ->get();

        $odt_folder = storage_path('app/public').'/'.auth()->user()->code;

        $zip = new ZipArchive;
        if(file_exists($odt_folder.'/'.$year.'年級'.$sex.'子組'.$item->name.'獎狀.odt')){
            unlink($odt_folder.'/'.$year.'年級'.$sex.'子組'.$item->name.'獎狀.odt');
        }

        if ($zip->open($odt_folder.'/'.$year.'年級'.$sex.'子組'.$item->name.'獎狀.odt', ZipArchive::CREATE) === TRUE) {
            $zip->addFile($odt_folder . '/demo/manifest.rdf', 'manifest.rdf');
            $zip->addFile($odt_folder . '/demo/meta.xml', 'meta.xml');
            $zip->addFile($odt_folder . '/demo/settings.xml', 'settings.xml');

            $content = $odt_folder . '/demo/content.xml';
            if (file_exists($content)) {
                $fp = fopen($content, "r");
                $str = fread($fp, filesize($content));//指定讀取大小，這裡把整個檔案內容讀取出來

                $d = explode('<office:body>',$str);
                $odt_head = $d[0];
                $a = explode('</office:body>',$d[1]);
                $odt_body = $a[0];
                $odt_foot = $a[1];

                $data_body = null;
                $y = date('Y') - 1911;

                $score_data = [];
                $group_student = [];
                $i = 0;
                $item_name = $item->name;
                $print_date = "中華民國".$y.'年'.date('m').'月'.date('d').'日';
                $print_date_c = date('Y').'年'.date('m').'月'.date('d').'日';
                //取代
                $action_name = str_replace('報名','',$action->name);

                $first = $student_signs->first();
                $last_group =$first->student_year.$first->student_class.$first->group_num;
                foreach($student_signs as $student_sign) {
                    //if(!empty($student_sign->achievement)){
                        if($student_sign->student_year=="幼小" or $student_sign->student_year=="幼中" or $student_sign->student_year=="幼大"){
                            $score_data[$i]['year_name'] = $cht_num[$student_sign->student_year];
                            $score_data[$i]['year_class_name'] = $cht_num[$student_sign->student_year].$student_sign->student_class . "班";
                            $score_data[$i]['cht_year_class_name'] = $cht_num[$student_sign->student_year] . $cht_num[$student_sign->student_class] . "班";
                        }else{
                            $score_data[$i]['year_name'] = $cht_num[$student_sign->student_year] . "年級";
                            $score_data[$i]['year_class_name'] = $cht_num[$student_sign->student_year] . "年" . $student_sign->student_class . "班";
                            $score_data[$i]['cht_year_class_name'] = $cht_num[$student_sign->student_year] . "年" . $cht_num[$student_sign->student_class] . "班";
                        }
                        $score_data[$i]['class_name'] = $student_sign->student_class . "班";
                        $score_data[$i]['cht_class_name'] = $cht_num[$student_sign->student_class] . "班";

                        $score_data[$i]['ranking'] = $student_sign->ranking;
                        $score_data[$i]['achievement'] = $student_sign->achievement;
                        if($student_sign->is_official){
                            $score_data[$i]['this_student'] = $student_sign->student->name;
                        }else{
                            $score_data[$i]['this_student'] = $student_sign->student->name."(候)";
                        }
                        $score_data[$i]['group'] = ($sex=="4")?"不分性別組":$sex."子組";
                        $i++;

                    //}
                }

                $i=0;
                foreach($score_data as $k=>$v){
                    if($i<$item->reward*($item->official+$item->reserve)){
                        $str2 = str_replace("{{年班同學}}", $v['year_class_name']." ".$v['this_student'], $odt_body);
                        $str2 = str_replace("{{姓名}}", $v['this_student'], $str2);
                        $str2 = str_replace("{{年班}}", $v['year_class_name'], $str2);
                        $str2 = str_replace("{{國字年班}}", $v['cht_year_class_name'], $str2);
                        $str2 = str_replace("{{年級}}", $v['year_name'], $str2);
                        $str2 = str_replace("{{班別}}", $v['class_name'], $str2);
                        $str2 = str_replace("{{國字班別}}", $v['cht_class_name'], $str2);
                        $str2 = str_replace("{{運動會名稱}}", $action_name, $str2);
                        $str2 = str_replace("{{組別}}", $v['group'], $str2);
                        $str2 = str_replace("{{項目}}", $item_name, $str2);
                        $str2 = str_replace("{{名次}}", "第".$v['ranking']."名", $str2);
                        $str2 = str_replace("{{國字名次}}", "第".$cht_num[$v['ranking']]."名", $str2);
                        $str2 = str_replace("{{成績}}", $v['achievement'], $str2);
                        $str2 = str_replace("{{日期}}", $print_date, $str2);
                        $str2 = str_replace("{{西元日期}}", $print_date_c, $str2);

                        $data_body .= $str2;
                        $i++;
                    }
                }

                $odt = $odt_head."<office:body>".$data_body."</office:body>".$odt_foot;

                //dd($str);

                //寫入 content2.xml
                //先刪除
                if(file_exists($odt_folder . '/demo/content2.xml')){
                    unlink($odt_folder . '/demo/content2.xml');
                }
                $fp2 = fopen($odt_folder . '/demo/content2.xml', "a+"); //開啟檔案
                fwrite($fp2, $odt);
                fclose($fp2);

                $zip->addFile($odt_folder . '/demo/content2.xml', 'content.xml');
                $zip->addFile($odt_folder . '/demo/mimetype', 'mimetype');
                $zip->addFile($odt_folder . '/demo/styles.xml', 'styles.xml');
                $zip->addFile($odt_folder . '/demo/META-INF/manifest.xml', 'META-INF/manifest.xml');


                $zip->close();
            }

            return response()->download($odt_folder.'/'.$year.'年級'.$sex.'子組'.$item->name.'獎狀.odt');
        }


    }

    public function print_extra(Request $request)
    {
        $odt_folder = storage_path('app/public').'/'.auth()->user()->code;

        $zip = new ZipArchive;
        if(file_exists($odt_folder.'/自訂獎狀.odt')){
            unlink($odt_folder.'/自訂獎狀.odt');
        }
        if ($zip->open($odt_folder.'/自訂獎狀.odt', ZipArchive::CREATE) === TRUE) {
            $zip->addFile($odt_folder . '/demo/manifest.rdf', 'manifest.rdf');
            $zip->addFile($odt_folder . '/demo/meta.xml', 'meta.xml');
            $zip->addFile($odt_folder . '/demo/settings.xml', 'settings.xml');

            $content = $odt_folder . '/demo/content.xml';
            if (file_exists($content)) {
                $fp = fopen($content, "r");
                $str = fread($fp, filesize($content));//指定讀取大小，這裡把整個檔案內容讀取出來
                $this_student = $request->input('this_student');
                $action_name = $request->input('action_name');
                $group = $request->input('group');
                $item = $request->input('item');
                $ranking = $request->input('ranking');
                $score = $request->input('score');
                $print_date = $request->input('print_date');

                //取代
                $str = str_replace("{{年班同學}}", $this_student, $str);
                $str = str_replace("{{運動會名稱}}", $action_name, $str);
                $str = str_replace("{{組別}}", $group, $str);
                $str = str_replace("{{項目}}", $item, $str);
                $str = str_replace("{{名次}}", $ranking, $str);
                $str = str_replace("{{成績}}", $score, $str);
                $str = str_replace("{{日期}}", $print_date, $str);

                //寫入 content2.xml
                //先刪除
                if(file_exists($odt_folder . '/demo/content2.xml')){
                    unlink($odt_folder . '/demo/content2.xml');
                }
                $fp2 = fopen($odt_folder . '/demo/content2.xml', "a+"); //開啟檔案
                fwrite($fp2, $str);
                fclose($fp2);

                $zip->addFile($odt_folder . '/demo/content2.xml', 'content.xml');
                $zip->addFile($odt_folder . '/demo/mimetype', 'mimetype');
                $zip->addFile($odt_folder . '/demo/styles.xml', 'styles.xml');
                $zip->addFile($odt_folder . '/demo/META-INF/manifest.xml', 'META-INF/manifest.xml');


                $zip->close();
            }

            return response()->download($odt_folder . '/自訂獎狀.odt');
        }

    }

    public function demo_upload(Request $request)
    {
        $school_code = auth()->user()->code;
        $folder = 'public/'. $school_code ;
        //處理檔案上傳
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
