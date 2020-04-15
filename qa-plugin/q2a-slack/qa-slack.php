<?php
/*
        q2a-slack by Leonard Challis
        http://www.leonardchallis.com/

        for
            
        Question2Answer by Gideon Greenspan and contributors
        http://www.question2answer.org/

        File: qa-plugin/slack/qa-plugin.php
        Description: Posts information to Slack
 */
class qa_slack
{

    // change these three lines
    private $siteUrl = 'http://192.168.4.19/';
    private $slackUrl = 'https://hooks.slack.com/services/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';
    private $linkMessage = 'Ver no kperguntas!';

    public function process_event($event, $userid, $handle, $cookieid, $params)
    {
        $eventDescription = null;

        switch ($event) {
            case 'q_post':
                $eventDescription = 'fazer uma pergunta: `' . $params['title'] . "` <" . $this->siteUrl . $params['postid'] . "|" . $this->linkMessage . ">";
                break;
            case 'a_post':
                $eventDescription = 'responder ' . ($params['parent']['handle'] == $handle ? 'sua própria' : $params['parent']['handle'] . "") . " pergunta `" . $params['parent']['title'] . "` <" . $this->siteUrl . $params['parent']['postid'] . "|" . $this->linkMessage . ">";
                break;
            case 'c_post':
                /*
                $type = ($params['parenttype'] == 'A') ? 'answer for question' : $type = 'question';
                $eventDescription = 'commented on ' . ($params['parent']['handle'] == $handle ? 'their own' : $params['parent']['handle'] . "'s") . " $type `" . $params['question']['title'] . "`\n<" . $this->siteUrl . $params['questionid'] . "|" . $this->linkMessage . ">";
                break;
                */
                return;
            case 'q_edit':
                /*
                $eventDescription = 'edited ' . ($params['oldquestion']['handle'] == $handle ? 'their own' : $params['oldquestion']['handle'] . "'s") . " question `" . $params['title'] . "`\n<" . $this->siteUrl . $params['oldquestion']['postid'] . "|" . $this->linkMessage . ">";
                break;
                */
                return;
            case 'a_edit':
                /*
                $eventDescription = 'edited ' . ($params['oldanswer']['handle'] == $handle ? 'their own' : $params['oldanswer']['handle'] . "'s") . " answer for `" . $params['parent']['title'] . "`\n<" . $this->siteUrl . $params['parent']['postid'] . "|" . $this->linkMessage . ">";
                break;
                */
                return;
            case 'c_edit':
                /*
                $type = ($params['parenttype'] == 'A') ? 'answer for question' : $type = 'question';
                $eventDescription = 'edited ' . ($params['oldcomment']['handle'] == $handle ? 'their own' : $params['oldcomment']['handle'] . "'s") . ' comment on ' . ($params['parent']['handle'] == $handle ? 'their own' : $params['parent']['handle'] . "'s") . " $type `" . $params['question']['title'] . "`\n<" . $this->siteUrl . $params['questionid'] . "|" . $this->linkMessage . ">";
                break;
                */
                return;
            case 'a_select':
                /*
                $eventDescription = 'selected ' . ($params['answer']['handle'] == $handle ? 'their own' : $params['answer']['handle'] . "'s") . " answer for " . ($params['parent']['handle'] == $handle ? 'their own' : $params['parent']['handle'] . "'s") . " question `" . $params['parent']['title'] . "`\n<" . $this->siteUrl . $params['parent']['postid'] . "|" . $this->linkMessage . ">";
                break;
                */
                return;
        }

        if (null === $eventDescription) {
            return;
        }

        $message = "$handle acabou de $eventDescription";

        $data = array('text' => $message, 'icon_emoji' => ':question:', 'username' => 'KPerguntas');
        $data_string = json_encode($data);

        $ch = curl_init($this->slackUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        /* un-comment out the line below to stop cURL checking if it trusts the address*/
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($data_string)));

        $result = curl_exec($ch);
    }
}

