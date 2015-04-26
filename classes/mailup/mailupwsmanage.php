<?php

class MailUpWsManage
{

    //Il rispettivo WSDL è disponibile all'indirizzo:
    protected $WSDLUrl = "https://wsvc.ss.mailup.it/MailupManage.asmx?WSDL";
    private $soapClient;
    private $accessKey = '';

    public function __construct()
    {
        $this->soapClient = new SoapClient($this->WSDLUrl, array(
            'soap_version' => SOAP_1_2,
            'encoding' => 'UTF-8',
            'exceptions' => true
        ));
    }

    public function __destruct()
    {
        unset($this->soapClient);
    }

    private function getArrayResult($result_string, $tags)
    {
        $return_array = array();
        $dom = new DomDocument();
        if ($dom->loadXML($result_string) === FALSE) {
            throw new Exception('Unable to parse response: ' . $result_string, 662);
        }

        foreach ($tags as $t) {
            $itemnode = $dom->getElementsByTagName($t)->item(0);

            if ($itemnode === NULL) {
                throw new Exception('Unable to get tag node: ' . $t . ' from response: ' . $result_string, 663);
            }

            $return_array[$t] = $itemnode->nodeValue;
        }
        return $return_array;
    }

    //Pg 64 sezione 13.2.1 METODO LOGINFROMID
    public function LoginFromId($user, $pwd, $consoleId)
    {
        $ret = $this->getArrayResult(
                $this->soapClient->LoginFromId(array('user' => $user, 'pwd' => $pwd, 'consoleId' => $consoleId))->LoginFromIdResult, array('errorCode', 'errorDescription', 'accessKey')
        );
        if ($ret['errorCode'] != '0') {
            throw new Exception('LoginFromId: ' . $ret['errorDescription'], $ret['errorCode']);
        }
        $this->accessKey = $ret['accessKey'];
    }

    //Pg 65 sezione 13.2.2 METODO LOGOUT
    public function Logout()
    {
        if (empty($this->accessKey)) {
            return;
        }
        $ret = $this->getArrayResult(
                $this->soapClient->Logout(array('accessKey' => $this->accessKey))->LogoutResult, array('errorCode', 'errorDescription')
        );
        if ($ret['errorCode'] != '0') {
            throw new Exception('Logout: ' . $ret['errorDescription'], $ret['errorCode']);
        }
        $this->accessKey = '';
    }

    /**
     * Get available lists
     *
     * @return array
     * @throws Exception
     */
    public function getLists()
    {
        $lists = array();

        if (empty($this->accessKey)) {
            throw new Exception('getLists: Invalid accessKey', 401);
        }

        $result = $this->soapClient->getLists(array('accessKey' => $this->accessKey))->GetListsResult;

        $dom = new DomDocument();
        if ($dom->loadXML($result) === FALSE) {
            return array();
        }

        $error_code = $dom->getElementsByTagName('errorCode')->item(0)->nodeValue;
        if ($error_code != 0) {
            return array();
        }

        foreach ($dom->getElementsByTagName('lists')->item(0)->childNodes as $list) {
            $listId = $list->getElementsByTagName('listID')->item(0)->nodeValue;
            $listName = $list->getElementsByTagName('listName')->item(0)->nodeValue;
            $lists[$listId] = $listName;
        }

        return $lists;
    }

    /**
     * Get existing groups for given list ID
     *
     * @param int $listID
     * @return array
     * @throws Exception
     */
    public function getGroups($listID)
    {
        $groups = array();

        if (empty($this->accessKey)) {
            throw new Exception('getGroups: Invalid accessKey', 401);
        }

        if (empty($listID)) {
            throw new Exception('getGroups: No list given', 400);
        }

        $result = $this->soapClient->getGroups(array('accessKey' => $this->accessKey, 'listID' => $listID))->GetGroupsResult;

        $dom = new DomDocument();
        if ($dom->loadXML($result) === FALSE) {
            return array();
        }

        $error_code = $dom->getElementsByTagName('errorCode')->item(0)->nodeValue;
        if ($error_code != 0) {
            return array();
        }

        foreach ($dom->getElementsByTagName('groups')->item(0)->childNodes as $group) {
            $groupId = $group->getElementsByTagName('groupID')->item(0)->nodeValue;
            $groupName = $group->getElementsByTagName('groupName')->item(0)->nodeValue;
            $groups[$groupId] = $groupName;
        }

        return $groups;
    }

    /**
     *
     * @param int $listID
     * @param string $groupName
     * @param string $groupNotes
     * @return int
     * @throws Exception
     */
    public function createGroup($listID, $groupName, $groupNotes = "")
    {
        if (empty($this->accessKey)) {
            throw new Exception('getGroups: Invalid accessKey', 401);
        }

        if (empty($listID)) {
            throw new Exception('getGroups: No list given', 400);
        }

        $result = $this->soapClient->createGroup(array(
                    'accessKey' => $this->accessKey,
                    'listID' => $listID,
                    'groupName' => $groupName,
                    'groupNotes' => $groupNotes
                ))->CreateGroupResult;

        $dom = new DomDocument();
        if ($dom->loadXML($result) === FALSE) {
            return array();
        }

        $error_code = $dom->getElementsByTagName('errorCode')->item(0)->nodeValue;
        if ($error_code != 0) {
            return array();
        }

        foreach ($dom->getElementsByTagName('groups')->item(0)->childNodes as $group) {
            $groupId = $group->getElementsByTagName('groupID')->item(0)->nodeValue;
            return $groupId;
        }
        
        return 0;
    }

}
