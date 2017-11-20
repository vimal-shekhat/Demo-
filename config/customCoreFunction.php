<?php

function fnIsNull($data) {
    return (@trim($data) === "" or $data === null or ! isset($data) or ! count($data) ) ? true : false;
}

// your rules here //// if value is zero then return true  ;// Dmpatel
function fnIsNotNull($data) {
    return (@trim($data) === "" or $data === null or ! isset($data) or ! count($data) ) ? false : true;
}

function fnMappingMasterData(&$data = null) {
    if (fnIsNotNull($data)) {

        $currentDate = date('Y-m-d H:i:s');

        $currentUserId = currentUserId;
        $created_by = $currentUserId;
        $created_date = $currentDate;
        $updated_by = $currentUserId;
        $updated_date = $currentDate;
        $status = '1';
        $deleted_flag = '0';

        foreach ($data as $k => &$d) {
            if (is_array($d)) {

                if (empty($d['id'])) {
                    $d['created_by'] = $created_by;
                    $d['created_date'] = $created_date;
                }
                $d['updated_by'] = $updated_by;
                $d['updated_date'] = $updated_date;
                if (!isset($d['status'])) {
                    //    $d['status'] = $status;
                }
                if (!isset($d['deleted_flag'])) {
                    //   $d['deleted_flag'] = $deleted_flag;
                }
            } else {
                if (empty($data['id'])) {
                    $data['created_by'] = $created_by;
                    $data['created_date'] = $created_date;
                }
                $data['updated_by'] = $updated_by;
                $data['updated_date'] = $updated_date;

                if (!isset($d['status'])) {
                    //   $data['status'] = $status;
                }
                if (!isset($d['deleted_flag'])) {
                    //  $data['deleted_flag'] = $deleted_flag;
                }
                break;
            }
        }
    }
}

function GetRequestedField($reqArr, $field, $uniqueResult = 0, $setQute = 0, $checkNull = 0, $field2 = null) {
    $genArr = array();
    if (is_array($reqArr) && count($reqArr) > 0) {
        foreach ($reqArr as $key => $val) {
            if ($checkNull == 1) {
                if (fnIsNull($val[$field])) {
                    continue;
                }
            }
            if (fnIsNotNull($field2)) {
                if (isset($val[$field]) && isset($val[$field][$field2])) {
                    $genArr[$key] = $val[$field][$field2];
                }
            } else {
                if (isset($val[$field])) {
                    $genArr[$key] = $val[$field];
                }
            }
            if ($setQute == 1) {
                $genArr[$key] = "'" . $genArr[$key] . "'";
            }
        }
    }
    if ($uniqueResult == 1) {
        $genArr = array_unique($genArr);
    }
    return $genArr;
}

function cleanArr(&$data)
{
  if (fnIsNull($data) || !is_array($data))
  {
    return $data;
  }
  foreach ($data as $key => &$val)
  {
    if (!is_array($val))
      $val = trim($val);
    if (fnIsNotNull($val) && is_array($val))
    {
      cleanArr($val);
    }
    elseif (fnIsNull($val))
    {
      unset($data[$key]);
    }
  }
  return $data;
}

function getAssociateArr($data, $askey, $type = '', $askey2 = null)
{
  $data1 = array();
  if (is_array($data))
  {

    if ($type == 'multi')
    {
      foreach ($data as $key => $val)
      {
        if (empty($askey2))
        {
          $data1[$val[$askey]][] = $val;
        }
        else
        {
          $data1[$val[$askey][$askey2]][] = $val;
        }
      }
    }
    else
    {
      foreach ($data as $key => $val)
      {

        if (empty($askey2))
        {
          $data1[$val[$askey]] = $val;
        }
        else
        {
          $data1[$val[$askey][$askey2]] = $val;
        }
      }
    }
  }
  return $data1;
}
