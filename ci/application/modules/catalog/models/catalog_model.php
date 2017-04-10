<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalog_Model extends MY_Model
{
    protected $_shipmentMethods,
              $_shipmentDates;

    public function __construct()
    {
        parent::__construct();

        $this->_getShipmentDates();
        $this->_getShipmentMethods();

    }

    protected function _getShipmentMethods()
    {
        try {
            $this->_shipmentMethods = $this->_client->getShipmentMethods()->item;
        }
        catch (SoapFault $E) {
            echo $E->faultstring;
        }
    }

    public function getFirstShipmentMethodsCode()
    {
        return $this->_shipmentMethods[0]->Code;
    }

    protected function _getShipmentDates()
    {
        try {
            $this->_shipmentDates = $this->_client->getShipmentDates()->item;
        }
        catch (SoapFault $E) {
            echo $E->faultstring;
        }
    }

    public function getFirstShipmentDates()
    {
        return $this->_shipmentDates[0]->Date;
    }

    public function getItems($catID = NULL, $itemID = NULL)
    {
        try {
            $response = $this->_client->getItems($catID, $itemID);

            return $response->item;
        }
        catch (SoapFault $E) {
            echo $E->faultstring;
        }
    }





    public function getItemsImages($catID = NULL, $itemID = NULL)
    {

        try {
            $response = $this->_client->getItemsImages($catID, $itemID);

            return $response->item;
        }
        catch (SoapFault $E) {
            echo $E->faultstring;
        }
    }

    public function getAllItems()
    {
        try {
            $response = $this->_client->getItems();

            return $response->item;
        }
        catch (SoapFault $E) {
            echo $E->faultstring;
        }
    }

    /*public function getAllItemsImages()
    {
        $arr = array();

        try {

            $response = $this->_client->getItems()->item;
           // var_dump_print($response);
          //  die (count($response));
            foreach($response as $item):
               // echo $item->No . '<br/>';
                $imgs = $this->_client->getItemsImages($item->GroupCode3, $item->No)->item;
               // var_dump_print($imgs);
                foreach($imgs as $img):
                   // var_dump_print($img);
                    $arr[] = $img->FileName;
                endforeach;
            endforeach;

            //$response = $this->_client->getItemsImages($catID,$itemID);
            //die(count($arr));
            return $arr;
        }
        catch (SoapFault $E) {
            echo $E->faultstring;
        }
    }*/

    public function getItemsProperties($itemID)
    {
        try {
            $response = $this->_client->getItemsProperties(NULL, $itemID);

            return $response->item;
        }
        catch (SoapFault $E) {
            echo $E->faultstring;
        }
    }

    public function getItemsAvail($catID = NULL, $itemID = NULL)
    {
        try {
            $response = $this->_client->getItemsAvail($catID, $this->getFirstShipmentMethodsCode(), $this->getFirstShipmentDates(), 0, $itemID);

            return $response->item;
        }
        catch (SoapFault $E) {
            echo $E->faultstring;
        }
    }




}