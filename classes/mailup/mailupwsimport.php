<?php

class MailUpWsImport
{

    protected $WSDLPath = "/Services/WSMailupImport.asmx?WSDL";
    protected $consoleUrl;
    private $soapClient;
    private $accessKey = '';
    private $username;
    private $password;

    public function __construct($console_url)
    {
        $this->consoleUrl = $console_url;
        $this->soapClient = new SoapClient($this->consoleUrl . $this->WSDLPath, array(
            'soap_version' => SOAP_1_2,
            'encoding' => 'UTF-8',
            'exceptions' => true,
            'trace' => true
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

    /**
     *
     * @param string $username
     * @param string $password
     * @return boolean
     * @throws Exception
     */
    public function Activate($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        $activation_url = $this->consoleUrl
                . "/frontend/WSActivation.aspx"
                . "?usr=$username"
                . "&pwd=$password"
                . "&nl_url=" . str_replace('http://', '',  $this->consoleUrl)
                . "&ws_name=WSMailUpImport";

        $ret = $this->getArrayResult(
                $this->getCurl($activation_url), array("ReturnCode")
        );
        if ($ret['ReturnCode'] != 0)
        {
            throw new Exception('Activate failed! Return code: ' . $ret['ReturnCode']);
        }

        return true;
    }


    /**
     * Import users
     *
     * @return array
     * @throws Exception
     */
    public function importUsers($listId, $listGuid, $groupId, $users = array())
    {
        if (empty($listId) || empty($listGuid) || empty($groupId) || empty($users)) {
            throw new Exception('importUsers: One or more required parameters are missing', 400);
        }

        $this->setAuthenticationHeader();

        $result = $this->soapClient->StartImportProcesses(array(
            'listsIDs' => $listId,
            'listsGUIDs' => $listGuid,
            'xmlDoc' => $this->createXmlDoc($users),
            'groupsIDs' => $groupId,
            'importType' => 1,
            'mobileInputType' => 1,
            'asPending' => false,
            'ConfirmEmail' => false,
            'asOptOut' => false,
            'forceOptIn' => false,
            'replaceGroups' => false
        ))->StartImportProcessesResult;

        $ret = $this->getArrayResult(
                $result, array("ReturnCode")
        );
        if ($ret['ReturnCode'] != 0)
        {
            throw new Exception('Activate failed! Return code: ' . $ret['ReturnCode']);
        }

        return true;
    }

    private function createXmlDoc($users)
    {
        if (empty($users)) {
            throw new Exception('createXmlDoc: no users given', 400);
        }

        $subscribers = array();
        foreach ($users as $user) {
            $subscribers [] = '<subscriber email="' . $user->email . '" Prefix="" Number="" Name="' . $user->firstname . ' ' . $user->lastname. '">'
                    . '<campo1>' . $user->firstname . '</campo1>'
                    . '<campo2>' . $user->lastname . '</campo2>'
                    . '</subscriber>';
        }

        return "<subscribers>" . implode("", $subscribers) . "</subscribers>";
    }

    private function getCurl($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        return curl_exec($curl);
    }

    private function setAuthenticationHeader()
    {
        $xml = "<ns1:Authentication><ns1:User>$this->username</ns1:User><ns1:Password>$this->password</ns1:Password><ns1:encType>UTF-8</ns1:encType></ns1:Authentication>";
        $authentication = new SoapVar($xml, XSD_ANYXML);
        $authenticationHeader = new SoapHeader('ns1', 'Authentication', $authentication);
        $this->soapClient->__setSoapHeaders($authenticationHeader);
    }

}
