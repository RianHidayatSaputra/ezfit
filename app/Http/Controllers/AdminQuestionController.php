<?php namespace App\Http\Controllers;

use App\Models\MsQuestion;
use crocodicstudio\crudbooster\controllers\CBController;

class AdminQuestionController extends CBController {


    public function cbInit()
    {
        $this->setTable("ms_question");
        $this->setPermalink("question");
        $this->setPageTitle("Question");

        $this->addText("Name","name")->strLimit(150)->maxLength(255);
		$this->addText("Uturan","order")->strLimit(150)->maxLength(255);
		$this->addText("Type Question","type_question")->strLimit(150)->maxLength(255);
		$this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
    }
    public function  getAdd(){
        $data['page_title'] = 'Add Question';

        return view('backend.question.add',$data);
    }
    public function postAddSave(){
        $q = g('choise');
        $a = json_encode($q);

        $save = New MsQuestion();
        $save->setName(g('name'));
        $save->setTypeQuestion(g('type_question'));
        $save->setShowButtonNext(g('show_button_next'));
        $save->setContent($a);
        $save->save();

        return redirect()->back()->with(["message_type"=>'success','message'=>'Success create question']);
    }
}
