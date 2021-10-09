<?php

function redirect($to)
{
    return header("Location:".$to);
}
 function back(){
    return "javascript:history.go(-1)";
 }