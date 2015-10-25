<?php
/*  ��������� ����� ������ � ����� ���������� �� �� �������� ����
 * $selectDate      - ��������� ����
 * $link            - ����� � XML �����
 * $xml_file        - ������ ��������, ����� ������ XML
 * $xml_save_path   - ���� ��� ���������� XML �����
 * $messageError    - ����� ������
 * --------------------------------------------------------------------------------------------------------------------
 * loadFileXml()          - �������� ������ �� ������������ ����� XML �����, ��� ���������� ��������� � ����� �����������
 * getCurrencyRate('USD') - ��������� ����� �������� ������
 * getFileName()          - ��������� ����� ����� ��� ���������� XML  ��������
 * saveFile()             - ���������� �����
 * allRate()              - ����� �������� �� XML  ����� � ������� mass['AUD'] = '������������� ������'
 * TODO ��� ������������� �������� ���� ���������� �����, ����� ���������� ����� allRate() �� ������� �������
 * TODO ����   mass['AUD'] = 46,5214. ����� ��������� � ��������� �������������� ������� ����� �������� ���� ������.
 */
class CurrencyRate {
    private $selectDate;
    private $link = "http://www.cbr.ru/scripts/XML_daily.asp?date_req=";        //���� � XML �����
    private $xml_file;
    private $xml_save_path;
    private $messageError = '';

    function __construct ($selectDate)
    {
        $this->selectDate = $selectDate;
        $this->xml_save_path = $_SERVER[DOCUMENT_ROOT].'/tmp/';
        $this->loadFileXml();
    }
    /////////////////////////////////////////////////////////////////////////
    private function loadFileXml()
    {
        $link = $this->getFileName();
        if (file_exists($link))     //��������� ������������� "����������" xml ���� � ��������
        {
            $this->xml_file = simplexml_load_file($link);

        } else                      //�������� XML ���� c ����� ����������� ��
        {
            $link = $this->link.$this->selectDate;
            if (!file_exists($link))
            {				
                $this->xml_file = simplexml_load_file($link);
                $this->saveFile();
            }
        }

        if (empty($this->xml_file))
        {
            $this->messageError .= "XML file doesn't exists! \n";
        }
    }
    /*
     * $codeVal is string. For example: "USD", "EURO"...
     * */
    ///////////////////////////////////////////////////////////////////////
    public function getCurrencyRate($codeVal)
    {
        for ($i = 0; $i < count($this->xml_file->Valute); $i++)
        {
            if ($this->xml_file->Valute[$i]->CharCode == trim($codeVal))
            {
                return $this->xml_file->Valute[$i]->Value;
            }
        }
        $this->messageError .= "This valuta is not correct!";
        return $this->messageError;
    }
    ///////////////////////////////////////////////////////////////////
    private function getFileName()
    {
        $curDate  = explode('/',$this->selectDate);
        return 'Valuta_'.$curDate[0].$curDate[1].$curDate[2].'.xml';
    }
    ///////////////////////////////////////////////////////////////////
    private function saveFile()
    {
        $newfile = $this->xml_save_path.$this->getFileName();
        $link    = $this->link.$this->selectDate;
        if (!copy($link, $newfile))
        {
            $this->messageError .= "XML file doesn't copy:  $link...\n";
        }
    }
    ///////////////////////////////////////////////////////////////////
    public function allRate()
    {
        $massRate = array();
        for ($i = 0; $i < count($this->xml_file->Valute); $i++)
        {
            $tempIndex = $this->xml_file->Valute[$i]->CharCode;
            $massRate["$tempIndex"] = $this->xml_file->Valute[$i]->Name;
        }
        return $massRate;
    }
}
?>