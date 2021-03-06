<?
use Bitrix\Im as IM;

class CIMMessageParam
{
	public static function Set($messageId, $params = Array())
	{
		global $DB;
		$messageId = intval($messageId);

		if(!(is_array($params) || is_null($params)) || $messageId <= 0)
			return false;

		if (is_null($params) || count($params) <= 0)
		{
			$messageParameters = IM\MessageParamTable::getList(array(
				'select' => array('ID'),
				'filter' => array(
					'=MESSAGE_ID' => $messageId,
				),
			));
			while ($parameterInfo = $messageParameters->fetch())
			{
				IM\MessageParamTable::delete($parameterInfo['ID']);
			}
			return true;
		}
		$default = self::GetDefault();

		$arToDelete = array();
		foreach ($params as $key => $val)
		{
			if (isset($default[$key]) && $default[$key] == $val)
			{
				$arToDelete[$key] = array(
					"=MESSAGE_ID" => $messageId,
					"=PARAM_NAME" => $key,
				);
			}
		}

		$arToInsert = array();
		foreach($params as $k1 => $v1)
		{
			$name = substr(trim($k1), 0, 100);
			if(strlen($name))
			{
				if(!is_array($v1))
					$v1 = array($v1);

				if (empty($v1))
				{
					$arToDelete[$name] = array(
						"=MESSAGE_ID" => $messageId,
						"=PARAM_NAME" => $name,
					);
				}
				else
				{
					foreach($v1 as $v2)
					{
						$value = substr(trim($v2), 0, 100);
						if(strlen($value))
						{
							$key = md5($name).md5($value);
							$arToInsert[$key] = array(
								"MESSAGE_ID" => $messageId,
								"PARAM_NAME" => $name,
								"PARAM_VALUE" => $value,
							);
						}
					}
				}
			}
		}

		if(!empty($arToInsert))
		{
			$messageParameters = IM\MessageParamTable::getList(array(
				'select' => array('ID', 'PARAM_NAME', 'PARAM_VALUE'),
				'filter' => array(
					'=MESSAGE_ID' => $messageId,
				),
			));
			while($ar = $messageParameters->fetch())
			{
				$key = md5($ar["PARAM_NAME"]).md5($ar["PARAM_VALUE"]);
				if(array_key_exists($key, $arToInsert))
				{
					unset($arToInsert[$key]);
				}
				else if (isset($params[$ar["PARAM_NAME"]]))
				{
					IM\MessageParamTable::delete($ar['ID']);
				}
			}
		}

		foreach($arToInsert as $parameterInfo)
		{
			IM\MessageParamTable::add($parameterInfo);
		}

		foreach($arToDelete as $filter)
		{
			$messageParameters = IM\MessageParamTable::getList(array(
				'select' => array('ID'),
				'filter' => $filter,
			));
			while ($parameterInfo = $messageParameters->fetch())
			{
				IM\MessageParamTable::delete($parameterInfo['ID']);
			}
		}
	}

	public static function Get($messageId, $params = false)
	{
		global $DB;

		$arResult = array();
		if (is_array($messageId))
		{
			if (!empty($messageId))
			{
				foreach ($messageId as $key => $value)
				{
					$messageId[$key] = intval($value);
					$arResult[$messageId[$key]] = Array();
				}
			}
			else
			{
				return $arResult;
			}
		}
		else
		{
			$messageId = intval($messageId);
			$arResult[$messageId] = Array();
			if ($messageId <= 0)
			{
				return false;
			}
		}

		if (is_array($messageId))
			$whereMessageId = "MESSAGE_ID IN (".implode(',',$messageId).")";
		else
			$whereMessageId = "MESSAGE_ID = ".$messageId;

		$rs = $DB->Query("
			SELECT MESSAGE_ID, PARAM_NAME, PARAM_VALUE
			FROM b_im_message_param
			WHERE ".$whereMessageId."
			".($params && strlen($params) > 0 ? " AND PARAM_NAME = '".$DB->ForSQL($params)."'" : "")."
		", false, "File: ".__FILE__."<br>Line: ".__LINE__);
		while($ar = $rs->Fetch())
		{
			if (!isset($arResult[$ar["MESSAGE_ID"]][$ar["PARAM_NAME"]]))
			{
				$arResult[$ar["MESSAGE_ID"]][$ar["PARAM_NAME"]] = array();
			}
			$arResult[$ar["MESSAGE_ID"]][$ar["PARAM_NAME"]][] = $ar["PARAM_VALUE"];
		}
		if (is_array($messageId))
		{
			foreach ($messageId as $key)
			{
				$arResult[$key] = self::PrepareValues($arResult[$key]);
			}
			return $arResult;
		}
		else
		{
			return self::PrepareValues($arResult[$messageId]);
		}
	}

	public static function GetMessageIdByParam($paramName, $paramValue)
	{
		$arResult = Array();
		if (strlen($paramName) <= 0 || strlen($paramValue) <= 0)
		{
			return $arResult;
		}

		global $DB;

		$rs = $DB->Query("
			SELECT MESSAGE_ID, PARAM_NAME, PARAM_VALUE
			FROM b_im_message_param
			WHERE PARAM_NAME = '".$DB->ForSQL($paramName)."' AND PARAM_VALUE = '".$DB->ForSQL($paramValue)."'
		", false, "File: ".__FILE__."<br>Line: ".__LINE__);
		while($ar = $rs->Fetch())
		{
			$arResult[] = $ar["MESSAGE_ID"];
		}

		return $arResult;
	}

	public static function PrepareValues($value)
	{
		$arValues = Array();

		$arDefault = self::GetDefault();
		foreach($arDefault as $key => $default)
		{
			if (in_array($key, Array('IS_DELETED', 'IS_EDITED')))
			{
				$arValues[$key] = in_array($value[$key][0], Array('Y', 'N'))? $value[$key][0]: $default;
			}
			else if ($key == 'FILE_ID' || $key == 'LIKE')
			{
				if (is_array($value[$key]) && !empty($value[$key]))
				{
					foreach ($value[$key] as $k => $v)
					{
						$arValues[$key][$k] = intval($v);
					}
				}
				else if (!is_array($value[$key]) && intval($value[$key]) > 0)
				{
					$arValues[$key] = intval($value[$key]);
				}
				else
				{
					$arValues[$key] = $default;
				}
			}
			else
			{
				$arValues[$key] = $default;
			}
		}

		return $arValues;
	}

	public static function GetDefault()
	{
		$arDefault = Array(
			'LIKE' => Array(),
			'FILE_ID' => Array(),
			'IS_DELETED' => 'N',
			'IS_EDITED' => 'N',
			'ATTACHMENTS' => Array(),
		);

		return $arDefault;
	}
}
?>