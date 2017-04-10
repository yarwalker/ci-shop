<?php
class Tp extends CI_Model {
    
    public $D, $tpl;
    
    function __construct()
    {
        $this->D = array();
    }
    
    function load_tpl($tpl_name)
    {
        $TPL=$this->load->view('templates/'.$tpl_name, FALSE, TRUE);
        $pattern = '/{[A-Z0-9_]+}/';
        $pattern2 = '/{[a-z_]+}/';
        preg_match_all($pattern, $TPL, $MODULES); // nahodit metki na shaplony
        preg_match_all($pattern2, $TPL, $VALUES); // nahodit metki na peremennye
        

        foreach ($MODULES[0] as $MODULE)
        {
            $module = substr($MODULE,1,-1);
            if (!isset($this->D[$module]))
            {
                $this->D[$module] = '';
                $this->common->load_module(strtolower($module));     
            }
        }

        foreach ($VALUES[0] as $VALUE)
        {
            $value = substr($VALUE,1,-1);

            if (!isset($this->D[$value]))
            {
                $this->D[$value] = '';
            }

        }
        $this->D['TPL'] = $tpl_name;


    }  
    
    function print_page()
    {
        $this->parse('JS_DOC_READY', 'misc/doc_ready.js'); 
        $this->parser->parse('templates/'.$this->D['TPL'], $this->D);    
    }
    
    function parse($label, $tpl)
    {
        $TPL = $this->load->view($tpl, FALSE, TRUE);


        $pattern = '/{[A-Za-z0-9_]+}/';
        preg_match_all($pattern, $TPL, $MODULES); // nahodit metki v shablone
        foreach ($MODULES[0] as $MODULE)
        {
            $module = substr($MODULE,1,-1);
            if ( strpos($module,'MDL_') !== false ) 
            {    
                $this->common->load_module(strtolower($module)); // esli est' vnutrennie moduli
               // echo 111;
            }
            if ( !isset($this->D[$module]) )
                $this->D[$module] = $this->lang->line($module); // esli oni ne opredeleny, to smotrit v langs
        } 
        if (isset($this->D[$label]))
        {
            $this->D[$label] .= $this->parser->parse($tpl, $this->D, TRUE);
        }
        else
        {
            $this->D[$label] = $this->parser->parse($tpl, $this->D, TRUE);
        } 


    }
    
    function clear($label)
    {
        $this->D[$label]='';        
    }
    
    function kill($label)
    {
        unset($this->D[$label]);        
    }
    
    function assign($label, $value='')
    {
        if (is_array($label))
        {
            foreach ($label as $l=>$v)
            {
                $this->D[$l]=$v;
            }
        }
        else
        $this->D[$label]=$value;
    }
    
    function megaassign($label, $value)
    {
        if (isset($this->D[$label])) $this->D[$label].=$value;
        else $this->D[$label]=$value;
    }
    
     function get_value($table, $col, $id, $what='id')
    {
        $q='SELECT `'.$col.'` FROM `'.$table.'` WHERE '.$what.'=\''.$id.'\' LIMIT 1';
        $r=mysql_query($q);
        if (mysql_num_rows($r))
        {
            $row=mysql_fetch_array($r);
            return $row[$col];   
        }   
        else
        {
            return '';
        }
    }
    
    function get_count($table, $col, $id)
    {
        $q='SELECT count(id) as c FROM `'.$table.'` WHERE `'.$col.'`=\''.$id.'\'';
        $r=mysql_query($q);
        if (mysql_num_rows($r))
        {
            $row=mysql_fetch_array($r);
            return $row['c'];   
        }   
        else
        {
            return '0';
        }
    }
    
    function add_doc_ready($module, $model='')
    {
        if ($model) $model.='_';
        $this->parse('JS_DOC_READY', $module.'/'.$model.'doc_ready.js');
    }

    function add_js($file, $absolute=false) // file in /js
    {
        if (!$absolute) $file = SITEURL.'/js/'.$file;
        $this->megaassign('ADD_JS','<script type="text/javascript" src="'.$file.'"></script>'."\n");
    }
    
    function add_css($file, $absolute=false) // file in /js
    {
        if (!$absolute) $file = SITEURL.'/css/'.$file;
        $this->megaassign('ADD_CSS','<link rel="stylesheet" type="text/css" href="'.$file.'"> ');
    }
    
    function print_array($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }
    
    function p($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }
    
    function show_msg($msg, $class='msg_error', $label='MSG')
    {
        $this->megaassign($label, '<div class="'.$class.'">'.$msg.'</div>');
    }
    
    function get_d($label)
    {
        if (isset($this->D[$label]))
        {
            
            $pattern = array(
                "/\\\\/"  , "/\n/"    , "/\r/"    , "/\"/"    ,
                "/\'/"    , "//"
            );
            $replace = array(
                "\\\\\\\\", "\\n"     , "\\r"     , "\\\""    ,
                "\\'"     , "\\x26"   , "\\x3C"   , "\\x3E"
            );
            return preg_replace($pattern, '', $this->D[$label]);       
            //return $this->D[$label];
        } 
        else return '';
    }
    
    function post($var,$val=false)
    {
        if ($p=$this->input->post($var)) return $p;
        else return $val; 
    }
    
    function content($label)
    {
        $r=false;
        if (isset($this->D[$label]))
        {
            if ($this->D[$label]) $r=$this->D[$label];
        }
        return $r;
    }
    
    function now($sec=0)
    {
        $t=time()+$sec;
        return date('Y-m-d H:i:s', $t);
    }
    
    function link_it($text)
    {
        $text= preg_replace("/(^|[\n ])([\w]*?)((ht|f)tp(s)?:\/\/[\w]+[^ \,\"\n\r\t<]*)/is", "$1$2<a href=\"$3\"  target=\"_blank\">$3</a>", $text);
        $text= preg_replace("/(^|[\n ])([\w]*?)((www|ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href=\"http://$3\" target=\"_blank\">$3</a>", $text);
        //$text= preg_replace("/(^|[\n ])([a-z0-9&\-_\.]+?)@([\w\-]+\.([\w\-\.]+)+)/i", "$1<a href=\"mailto:$2@$3\">$2@$3</a>", $text);
        return($text);
    }
    
    function is_set($var)
    {
        if (isset($var))
        {
            if ($var) return true;
            else return false;
        }
        else
        {
            return false;
        }
    }
    
    function trim_text($text,$len)
    {
        if (mb_strlen($text,'UTF-8')>$len)
        {
            $text=mb_substr($text,0,$len,'UTF-8').'...';
        }
        return $text;
    }
        
}