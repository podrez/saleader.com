<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?include("lang/".LANGUAGE_ID."/template.php");?>
<?if(!empty($_GET["act"]) && CModule::IncludeModule("catalog") && CModule::IncludeModule("sale") && CModule::IncludeModule("iblock")){

	if($_GET["act"] == "upd"){
		echo CSaleBasket::Update(intval($_GET['id']), array(
		   "QUANTITY" => intval($_GET["q"]),
		   "DELAY" => "N"
		));
	}elseif($_GET["act"] == "del"){
		echo CSaleBasket::Delete(intval($_GET['id']));
	}
	elseif($_GET["act"] == "emp"){
		echo CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());
	}elseif($_GET["act"] == "coupon" && $_GET["value"]){
		$couponResult = CCatalogDiscountCoupon::SetCoupon($_GET["value"]);
		echo $couponResult === false ? CCatalogDiscountCoupon::ClearCoupon() : $couponResult;
	}

	// re calc delivery

	elseif ($_GET["act"] == "getProductPrices"){
	
		global $USER;

		$OPTION_CURRENCY  = CCurrency::GetBaseCurrency();

		$arID = array();
		$arBasketOrder = array("NAME" => "ASC", "ID" => "ASC");
		$arBasketUser = array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => SITE_ID, "ORDER_ID" => "NULL");
		$arBasketSelect = array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY",
				"CAN_BUY", "PRICE", "WEIGHT", "NAME", "CURRENCY", "CATALOG_XML_ID", "VAT_RATE",
				"NOTES", "DISCOUNT_PRICE", "PRODUCT_PROVIDER_CLASS", "DIMENSIONS", "TYPE", "SET_PARENT_ID", "DETAIL_PAGE_URL", "*"
		);

		$dbBasketItems = CSaleBasket::GetList($arBasketOrder, $arBasketUser, false, false, $arBasketSelect);

		$arResult["SUM"]          = 0;
		$arResult["ORDER_WEIGHT"] = 0;
		$arResult["SUM_DELIVERY"] = 0;

		$arResult["MAX_DIMENSIONS"] = array();
		$arResult["ITEMS_DIMENSIONS"] = array();

		while ($arItems = $dbBasketItems->Fetch()){

			CSaleBasket::UpdatePrice(
				$arItems["ID"],
				$arItems["CALLBACK_FUNC"],
				$arItems["MODULE"],
				$arItems["PRODUCT_ID"],
				$arItems["QUANTITY"],
				"N",
				$arItems["PRODUCT_PROVIDER_CLASS"]
			);

			array_push($arID, $arItems["ID"]);

			$arDim = $arItems["DIMENSIONS"] = $arItems["~DIMENSIONS"];

			if(is_array($arDim)){
				$arResult["MAX_DIMENSIONS"] = CSaleDeliveryHelper::getMaxDimensions(
					array(
						$arDim["WIDTH"],
						$arDim["HEIGHT"],
						$arDim["LENGTH"]
						),
					$arResult["MAX_DIMENSIONS"]);

				$arResult["ITEMS_DIMENSIONS"][] = $arDim;
			}
		}

		$dbBasketItems = CSaleBasket::GetList(
			$arBasketOrder,
			array(
				"ID" => $arID,
				"ORDER_ID" => "NULL"
			),
			false,
			false,
			$arBasketSelect
		);

		while ($arItems = $dbBasketItems->Fetch()){
		    $arResult["SUM"]    += ($arItems["PRICE"]  * $arItems["QUANTITY"]);
		    $arResult["ORDER_WEIGHT"] += ($arItems["WEIGHT"] * $arItems["QUANTITY"]);
		    $arResult["ITEMS"][$arItems["PRODUCT_ID"]] = $arItems;
		    $arResult["ID"][] = $arItems["PRODUCT_ID"];
		}

	   $arOrder = array(
			"SITE_ID" => $_GET["SITE_ID"],
			"USER_ID" => $USER->GetID(),
			"ORDER_PRICE" => $arResult["SUM"],
			"ORDER_WEIGHT" => $arResult["ORDER_WEIGHT"],
			"BASKET_ITEMS" => $arResult["ITEMS"],
			"PERSON_TYPE_ID" => $_GET["PERSON_TYPE_ID"],
			"PAY_SYSTEM_ID" => $_GET["PAY_SYSTEM_ID"],
			// "DELIVERY_ID" => $_GET["DELIVERY_ID"]
	   );
	   
	   $arOptions = array(
	      "COUNT_DISCOUNT_4_ALL_QUANTITY" => "Y",
	   );
	   
	   $arErrors = array();
	   $allSum = 0;

		CSaleDiscount::DoProcessOrder($arOrder, $arOptions, $arErrors);
		if(!empty($arOrder["BASKET_ITEMS"])){ 
			foreach ($arOrder["BASKET_ITEMS"] as $arItem){
				$arItem["~PRICE"] = round($arItem["PRICE"]);
				$arItem["~BASE_PRICE"] = $arItem["BASE_PRICE"];
				$arItem["SUM"] = FormatCurrency($arItem["PRICE"] * $arItem["QUANTITY"], $OPTION_CURRENCY);
				$arItem["PRICE"] = FormatCurrency($arItem["PRICE"], $OPTION_CURRENCY);
				$arItem["BASE_PRICE"] = FormatCurrency($arItem["BASE_PRICE"], $OPTION_CURRENCY);
				$arReturnItems[$arItem["ID"]] = $arItem;

			}
		}

		echo jsonMultiEn($arReturnItems);
	}

	elseif ($_GET["act"] == "reCalcDelivery") {
		
		global $USER;
		
		$FUSER_ID = CSaleBasket::GetBasketUserID();
		$OPTION_CURRENCY  = $arResult["BASE_LANG_CURRENCY"] = CCurrency::GetBaseCurrency();
		$SITE_ID = $_GET["SITE_ID"];


		CSaleBasket::UpdateBasketPrices($FUSER_ID, $SITE_ID);

		$res = CSaleBasket::GetList(
			array(
				"ID" => "ASC"
			),
			array(
					"FUSER_ID" => CSaleBasket::GetBasketUserID(),
					"LID" => $SITE_ID,
					"ORDER_ID" => "NULL"
				),
			false,
			false,
			array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY",
				"CAN_BUY", "PRICE", "WEIGHT", "NAME", "CURRENCY", "CATALOG_XML_ID", "VAT_RATE",
				"NOTES", "DISCOUNT_PRICE", "PRODUCT_PROVIDER_CLASS", "DIMENSIONS", "TYPE", "SET_PARENT_ID", "DETAIL_PAGE_URL", "*"
			)
		);

		if($res->SelectedRowsCount() <= 0){
			exit(
				jsonEn(
					array(
						"ERROR" => GetMessage("ORDER_EMPTY")
					)
				)
			);
		}

		while ($arRes = $res->GetNext()){

			$ORDER_DISCOUNT  += ($arRes["QUANTITY"] * $arRes["DISCOUNT_PRICE"]);
			$ORDER_WEIGHT    += ($arRes["WEIGHT"] * $arRes["QUANTITY"]);
			$ORDER_PRICE     += ($arRes["PRICE"] * $arRes["QUANTITY"]);
			$ORDER_QUANTITY  += $arRes["QUANTITY"];
			$ORDER_MESSAGE   .= "<tr><td>".$arRes["NAME"]."</td><td>".$arRes["QUANTITY"]."</td><td>".SaleFormatCurrency($arRes["PRICE"], $arRes["CURRENCY"])." ".$arRes["CURRENCY"]."</td></tr>";

			if (!CSaleBasketHelper::isSetItem($arRes))
				$arResult["BASKET_ITEMS"][$arRes["ID"]] = $arRes;

			$arDim = $arRes["DIMENSIONS"] = $arRes["~DIMENSIONS"];

			if(is_array($arDim)){
				$arResult["MAX_DIMENSIONS"] = CSaleDeliveryHelper::getMaxDimensions(
					array(
						$arRes["WIDTH"],
						$arRes["HEIGHT"],
						$arRes["LENGTH"]
						),
					$arResult["MAX_DIMENSIONS"]);

				$arResult["ITEMS_DIMENSIONS"][] = $arDim;
			}

		}

		if(!empty($_GET["LOCATION_ID"])){
			
			$dbLoc = CSaleLocation::GetList(array(), array("ID" => $_GET["LOCATION_ID"]), false, false, array("*"));
			if($arLoc = $dbLoc->Fetch()){
				$arResult["LOCATION"] = $arLoc;
				$arUserResult["DELIVERY_LOCATION"] = $arLoc["ID"];
				$arUserResult["DELIVERY_LOCATION_BCODE"] = $arLoc["CODE"];
			}

			$arLocs = CSaleLocation::GetLocationZIP($arUserResult["DELIVERY_LOCATION"]); 
			if(!empty($arLocs)){
				$arLocs = $arLocs->Fetch();
			}

			$locFrom = COption::GetOptionString("sale", "location", false, $SITE_ID);


			$dbPay = CSalePaySystem::GetList(
				$arOrder = Array(
					"SORT" => "ASC",
					"PSA_NAME" => "ASC"
				),
				Array(
					"ACTIVE" => "Y",
					"PERSON_TYPE_ID" => $_GET["PERSON_TYPE"]
				)
			);

			while ($arPay = $dbPay->Fetch()){
			
				if(empty($arResult["PAYSYSTEM"]["FIRST_ID"])){
					$arResult["PAYSYSTEM"]["FIRST_ID"] = $arPay["ID"];
				}
				
				$arResult["PAYSYSTEM"][$arPay["ID"]] = $arPay;
			
			}

			$_GET["PAYSYSTEM_ID"] = !empty($arResult["PAYSYSTEM"][$_GET["PAYSYSTEM_ID"]]) ? $_GET["PAYSYSTEM_ID"] : $arResult["PAYSYSTEM"]["FIRST_ID"];

			$arFilter = array(
				"COMPABILITY" => array(
					"WEIGHT" => $ORDER_WEIGHT,
					"PRICE" => $ORDER_PRICE,
					"LOCATION_FROM" => $locFrom,
					"LOCATION_TO" => $arUserResult["DELIVERY_LOCATION"],
					"LOCATION_ZIP" => !empty($arLocs["ZIP"]) ? $arLocs["ZIP"] : false,
					"MAX_DIMENSIONS" => $arResult["MAX_DIMENSIONS"],
					"ITEMS" => $arResult["BASKET_ITEMS"]
				)
			);

			$arUserResult["PAY_SYSTEM_ID"] = IntVal($arUserResult["PAY_SYSTEM_ID"]);
			$arUserResult["DELIVERY_ID"] = trim($arUserResult["DELIVERY_ID"]);
			$bShowDefaultSelected = True;
			$arD2P = array();
			$arP2D = array();
			$delivery = "";
			$bSelected = false;

			$dbRes = CSaleDelivery::GetDelivery2PaySystem(array());
			while ($arRes = $dbRes->Fetch())
			{
				$arD2P[$arRes["DELIVERY_ID"]][$arRes["PAYSYSTEM_ID"]] = $arRes["PAYSYSTEM_ID"];
				$arP2D[$arRes["PAYSYSTEM_ID"]][$arRes["DELIVERY_ID"]] = $arRes["DELIVERY_ID"];
				$bShowDefaultSelected = False;
			}

			if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "d2p")
				$arP2D = array();

			if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d")
			{
				if(IntVal($arUserResult["PAY_SYSTEM_ID"]) <= 0)
				{
					$bFirst = True;
					$arFilter = array(
						"ACTIVE" => "Y",
						"PERSON_TYPE_ID" => $arUserResult["PERSON_TYPE_ID"],
						"PSA_HAVE_PAYMENT" => "Y"
					);
					$dbPaySystem = CSalePaySystem::GetList(
								array("SORT" => "ASC", "PSA_NAME" => "ASC"),
								$arFilter
						);
					while ($arPaySystem = $dbPaySystem->Fetch())
					{
						if (IntVal($arUserResult["PAY_SYSTEM_ID"]) <= 0 && $bFirst)
						{
							$arPaySystem["CHECKED"] = "Y";
							$arUserResult["PAY_SYSTEM_ID"] = $arPaySystem["ID"];
						}
						$bFirst = false;
					}
				}
			}

			$bFirst = True;
			$bFound = false;
			$_SESSION["SALE_DELIVERY_EXTRA_PARAMS"] = array(); // here we will store params for params dialog

			//select calc delivery
			foreach($arDeliveryServiceAll as $arDeliveryService)
			{
				foreach ($arDeliveryService["PROFILES"] as $profile_id => $arDeliveryProfile)
				{
					if ($arDeliveryProfile["ACTIVE"] == "Y"
							&& (count($arP2D[$arUserResult["PAY_SYSTEM_ID"]]) <= 0
							|| in_array($arDeliveryService["SID"], $arP2D[$arUserResult["PAY_SYSTEM_ID"]])
							|| empty($arD2P[$arDeliveryService["SID"]])
							))
					{
						$delivery_id = $arDeliveryService["SID"];
						$arProfile = array(
							"SID" => $profile_id,
							"TITLE" => $arDeliveryProfile["TITLE"],
							"DESCRIPTION" => $arDeliveryProfile["DESCRIPTION"],
							"FIELD_NAME" => "DELIVERY_ID",
						);


						if((strlen($arUserResult["DELIVERY_ID"]) > 0 && $arUserResult["DELIVERY_ID"] == $delivery_id.":".$profile_id))
						{
							$arProfile["CHECKED"] = "Y";
							$arUserResult["DELIVERY_ID"] = $delivery_id.":".$profile_id;
							$bSelected = true;

							$arOrderTmpDel = array(
								"PRICE" => $arResult["ORDER_PRICE"],
								"WEIGHT" => $arResult["ORDER_WEIGHT"],
								"DIMENSIONS" => $arResult["ORDER_DIMENSIONS"],
								"LOCATION_FROM" => COption::GetOptionString('sale', 'location'),
								"LOCATION_TO" => $arUserResult["DELIVERY_LOCATION"],
								"LOCATION_ZIP" => $arUserResult["DELIVERY_LOCATION_ZIP"],
								"ITEMS" => $arResult["BASKET_ITEMS"],
								"EXTRA_PARAMS" => $arResult["DELIVERY_EXTRA"]
							);

							$arDeliveryPrice = CSaleDeliveryHandler::CalculateFull($delivery_id, $profile_id, $arOrderTmpDel, $arResult["BASE_LANG_CURRENCY"]);

							if ($arDeliveryPrice["RESULT"] == "ERROR")
							{
								$arResult["ERROR"][] = $arDeliveryPrice["TEXT"];
							}
							else
							{
								$arResult["DELIVERY_PRICE"] = roundEx($arDeliveryPrice["VALUE"], SALE_VALUE_PRECISION);
								$arResult["PACKS_COUNT"] = $arDeliveryPrice["PACKS_COUNT"];
							}
						}

						if (empty($arResult["DELIVERY"][$delivery_id]))
						{
							$arResult["DELIVERY"][$delivery_id] = array(
								"SID" => $delivery_id,
								"SORT" => $arDeliveryService["SORT"],
								"TITLE" => $arDeliveryService["NAME"],
								"DESCRIPTION" => $arDeliveryService["DESCRIPTION"],
								"PROFILES" => array(),
							);
						}

						$arDeliveryExtraParams = CSaleDeliveryHandler::GetHandlerExtraParams($delivery_id, $profile_id, $arOrderTmpDel, SITE_ID);

						if(!empty($arDeliveryExtraParams))
						{
							$_SESSION["SALE_DELIVERY_EXTRA_PARAMS"][$delivery_id.":".$profile_id] = $arDeliveryExtraParams;
							$arResult["DELIVERY"][$delivery_id]["ISNEEDEXTRAINFO"] = "Y";
						}
						else
						{
							$arResult["DELIVERY"][$delivery_id]["ISNEEDEXTRAINFO"] = "N";
						}

						if(!empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") !== false)
						{
							if($arUserResult["DELIVERY_ID"] == $delivery_id.":".$profile_id)
								$bFound = true;
						}

						$arResult["DELIVERY"][$delivery_id]["LOGOTIP"] = $arDeliveryService["LOGOTIP"];
						$arResult["DELIVERY"][$delivery_id]["PROFILES"][$profile_id] = $arProfile;
						$bFirst = false;
					}
				}
			}
			if(!$bFound && !empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") !== false)
				$arUserResult["DELIVERY_ID"] = "";

			/*Old Delivery*/
			$arStoreId = array();
			$arDeliveryAll = array();
			$bFound = false;
			$bFirst = true;

			$dbDelivery = CSaleDelivery::GetList(
				array("SORT"=>"ASC", "NAME"=>"ASC"),
				array(
					"LID" => SITE_ID,
					"+<=WEIGHT_FROM" => $arResult["ORDER_WEIGHT"],
					"+>=WEIGHT_TO" => $arResult["ORDER_WEIGHT"],
					"+<=ORDER_PRICE_FROM" => $arResult["ORDER_PRICE"],
					"+>=ORDER_PRICE_TO" => $arResult["ORDER_PRICE"],
					"ACTIVE" => "Y",
					"LOCATION" => $arUserResult["DELIVERY_LOCATION"],
				)
			);
			while ($arDelivery = $dbDelivery->Fetch())
			{
				$arStore = array();
				if (strlen($arDelivery["STORE"]) > 0)
				{
					$arStore = unserialize($arDelivery["STORE"]);
					foreach ($arStore as $val)
						$arStoreId[$val] = $val;
				}

				$arDelivery["STORE"] = $arStore;

				if (isset($_POST["BUYER_STORE"]) && in_array($_POST["BUYER_STORE"], $arStore))
				{
					$arUserResult['DELIVERY_STORE'] = $arDelivery["ID"];
				}

				$arDeliveryDescription = CSaleDelivery::GetByID($arDelivery["ID"]);
				$arDelivery["DESCRIPTION"] = htmlspecialcharsbx($arDeliveryDescription["DESCRIPTION"]);

				$arDeliveryAll[] = $arDelivery;

				if(!empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") === false)
				{
					if(IntVal($arUserResult["DELIVERY_ID"]) == IntVal($arDelivery["ID"]))
						$bFound = true;
				}
				if(IntVal($arUserResult["DELIVERY_ID"]) == IntVal($arDelivery["ID"]))
				{
					$arResult["DELIVERY_PRICE"] = roundEx(CCurrencyRates::ConvertCurrency($arDelivery["PRICE"], $arDelivery["CURRENCY"], $arResult["BASE_LANG_CURRENCY"]), SALE_VALUE_PRECISION);
				}
			}
			if(!$bFound && !empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") === false)
			{
				$arUserResult["DELIVERY_ID"] = "";
			}

			$arStore = array();
			$dbList = CCatalogStore::GetList(
				array("SORT" => "DESC", "ID" => "DESC"),
				array("ACTIVE" => "Y", "ID" => $arStoreId, "ISSUING_CENTER" => "Y", "+SITE_ID" => SITE_ID),
				false,
				false,
				array("ID", "TITLE", "ADDRESS", "DESCRIPTION", "IMAGE_ID", "PHONE", "SCHEDULE", "GPS_N", "GPS_S", "ISSUING_CENTER", "SITE_ID")
			);
			while ($arStoreTmp = $dbList->Fetch())
			{
				if ($arStoreTmp["IMAGE_ID"] > 0)
					$arStoreTmp["IMAGE_ID"] = CFile::GetFileArray($arStoreTmp["IMAGE_ID"]);

				$arStore[$arStoreTmp["ID"]] = $arStoreTmp;
			}

			$arResult["STORE_LIST"] = $arStore;

			if(!$bFound && !empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") === false)
				$arUserResult["DELIVERY_ID"] = "";

			foreach($arDeliveryAll as $arDelivery)
			{
				if (count($arP2D[$arUserResult["PAY_SYSTEM_ID"]]) <= 0 || in_array($arDelivery["ID"], $arP2D[$arUserResult["PAY_SYSTEM_ID"]]))
				{
					$arDelivery["FIELD_NAME"] = "DELIVERY_ID";
					if ((IntVal($arUserResult["DELIVERY_ID"]) == IntVal($arDelivery["ID"])))
					{
						$arDelivery["CHECKED"] = "Y";
						$arUserResult["DELIVERY_ID"] = $arDelivery["ID"];
						$arResult["DELIVERY_PRICE"] = roundEx(CCurrencyRates::ConvertCurrency($arDelivery["PRICE"], $arDelivery["CURRENCY"], $arResult["BASE_LANG_CURRENCY"]), SALE_VALUE_PRECISION);
						$bSelected = true;
					}
					if (IntVal($arDelivery["PERIOD_FROM"]) > 0 || IntVal($arDelivery["PERIOD_TO"]) > 0)
					{
						$arDelivery["PERIOD_TEXT"] = GetMessage("SALE_DELIV_PERIOD");
						if (IntVal($arDelivery["PERIOD_FROM"]) > 0)
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_FROM")." ".IntVal($arDelivery["PERIOD_FROM"]);
						if (IntVal($arDelivery["PERIOD_TO"]) > 0)
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_TO")." ".IntVal($arDelivery["PERIOD_TO"]);
						if ($arDelivery["PERIOD_TYPE"] == "H")
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_HOUR")." ";
						elseif ($arDelivery["PERIOD_TYPE"]=="M")
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_MONTH")." ";
						else
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_DAY")." ";
					}

					if (intval($arDelivery["LOGOTIP"]) > 0)
						$arDelivery["LOGOTIP"] = CFile::GetFileArray($arDelivery["LOGOTIP"]);

					$arDelivery["PRICE_FORMATED"] = SaleFormatCurrency($arDelivery["PRICE"], $arDelivery["CURRENCY"]);
					$arResult["DELIVERY"][$arDelivery["ID"]] = $arDelivery;
					$bFirst = false;
				}
			}

			uasort($arResult["DELIVERY"], array('CSaleBasketHelper', 'cmpBySort')); // resort delivery arrays according to SORT value

			if(!$bSelected && !empty($arResult["DELIVERY"]))
			{
				$bf = true;
				foreach($arResult["DELIVERY"] as $k => $v)
				{
					if($bf)
					{
						if(IntVal($k) > 0)
						{
							$arResult["DELIVERY"][$k]["CHECKED"] = "Y";
							$arUserResult["DELIVERY_ID"] = $k;
							$bf = false;

							$arResult["DELIVERY_PRICE"] = roundEx(CCurrencyRates::ConvertCurrency($arResult["DELIVERY"][$k]["PRICE"], $arResult["DELIVERY"][$k]["CURRENCY"], $arResult["BASE_LANG_CURRENCY"]), SALE_VALUE_PRECISION);
						}
						else
						{
							foreach($v["PROFILES"] as $kk => $vv)
							{
								if($bf)
								{
									$arResult["DELIVERY"][$k]["PROFILES"][$kk]["CHECKED"] = "Y";
									$arUserResult["DELIVERY_ID"] = $k.":".$kk;
									$bf = false;

									$arOrderTmpDel = array(
										"PRICE" => $arResult["ORDER_PRICE"],
										"WEIGHT" => $arResult["ORDER_WEIGHT"],
										"DIMENSIONS" => $arResult["ORDER_DIMENSIONS"],
										"LOCATION_FROM" => COption::GetOptionString('sale', 'location'),
										"LOCATION_TO" => $arUserResult["DELIVERY_LOCATION"],
										"LOCATION_ZIP" => $arUserResult["DELIVERY_LOCATION_ZIP"],
										"ITEMS" => $arResult["BASKET_ITEMS"],
										"EXTRA_PARAMS" => $arResult["DELIVERY_EXTRA"]
									);

									$arDeliveryPrice = CSaleDeliveryHandler::CalculateFull($k, $kk, $arOrderTmpDel, $arResult["BASE_LANG_CURRENCY"]);

									if ($arDeliveryPrice["RESULT"] == "ERROR")
									{
										$arResult["ERROR"][] = $arDeliveryPrice["TEXT"];
									}
									else
									{
										$arResult["DELIVERY_PRICE"] = roundEx($arDeliveryPrice["VALUE"], SALE_VALUE_PRECISION);
										$arResult["PACKS_COUNT"] = $arDeliveryPrice["PACKS_COUNT"];
									}
									break;
								}
							}
						}
					}
				}
			}

			if ($arUserResult["PAY_SYSTEM_ID"] > 0 || strlen($arUserResult["DELIVERY_ID"]) > 0)
			{
				if (strlen($arUserResult["DELIVERY_ID"]) > 0 && $arParams["DELIVERY_TO_PAYSYSTEM"] == "d2p")
				{
					if (strpos($arUserResult["DELIVERY_ID"], ":"))
					{
						$tmp = explode(":", $arUserResult["DELIVERY_ID"]);
						$delivery = trim($tmp[0]);
					}
					else
						$delivery = intval($arUserResult["DELIVERY_ID"]);
				}
			}

			if(DoubleVal($arResult["DELIVERY_PRICE"]) > 0)
				$arResult["DELIVERY_PRICE_FORMATED"] = SaleFormatCurrency($arResult["DELIVERY_PRICE"], $arResult["BASE_LANG_CURRENCY"]);

			foreach(GetModuleEvents("sale", "OnSaleComponentOrderOneStepDelivery", true) as $arEvent)
				ExecuteModuleEventEx($arEvent, array(&$arResult, &$arUserResult, &$arParams));

			echo jsonMultiEn($arResult["DELIVERY"]);

		}else{
			exit(
				jsonEn(
					array(
						"ERROR" => "Delivery error (3); Check field IS_LOCATION!!!."
					)
				)
			);
		}
	
	}

	##### ORDER #####

	elseif($_GET["act"] == "location" && !empty($_GET["q"])){

		$LOCATIONS = array();
		$CITY_NAME = (BX_UTF == 1) ? $_GET["q"] : iconv("UTF-8", "CP1251//IGNORE", $_GET["q"]);

		$dbLocations = CSaleLocation::GetList(
			array(
				"SORT" => "ASC",
				"COUNTRY_NAME_LANG" => "ASC",
				"CITY_NAME_LANG" => "ASC"
			),
			array(
				"LID" => LANGUAGE_ID,
				"%CITY_NAME" => $CITY_NAME
			),
			false,
			Array(
				"nPageSize" => 5,
			),
			array("*")
		);
		while ($arLoc = $dbLocations->Fetch()){
			if(!empty($arLoc["CITY_NAME"])){
				$arLoc["REGION_NAME"] = !empty($arLoc["REGION_NAME"]) ? $arLoc["REGION_NAME"].", " : "";
				$LOCATIONS[$arLoc["ID"]] = $arLoc["COUNTRY_NAME"].", ".$arLoc["REGION_NAME"].$arLoc["CITY_NAME"];
			}
		}
		echo jsonEn($LOCATIONS);
	}

	##### ORDER MAKE #####

	elseif ($_GET["act"] == "orderMake") {
		
		global $USER;
		
		$FUSER_ID = CSaleBasket::GetBasketUserID();
		$OPTION_CURRENCY  = CCurrency::GetBaseCurrency();
		$SITE_ID = $_GET["SITE_ID"];
		$DELIVERY_ID = intval($_GET["DEVIVERY_TYPE"]);
		$DELIVERY_CODE = !empty($_GET["DEVIVERY_TYPE"]) ? $_GET["DEVIVERY_TYPE"] : null;
		$SAVE_FIELDS = TRUE;
		
		if(!empty($_GET["USER_NAME"])){
			$USER_NAME = explode(" ", $_GET["USER_NAME"]);
		}

		if(!empty($_GET["PERSONAL_MOBILE"])){
			$PERSONAL_MOBILE = $_GET["PERSONAL_MOBILE"];
		}

		if(!empty($_GET["PERSONAL_ADDRESS"])){
			$PERSONAL_ADDRESS = $_GET["PERSONAL_ADDRESS"];
		}

		$db_props = CSaleOrderProps::GetList(
	        array("SORT" => "ASC"),
	        array(
	                "PERSON_TYPE_ID" => intval($_GET["PERSON_TYPE"]),
	                "IS_EMAIL" => "Y",
	                "CODE" => "EMAIL"
	            ),
	        false,
	        false,
	        array()
	    );

		if ($props = $db_props->Fetch()){
			if($props["REQUIED"] == "Y"){
				$OPTION_REGISTER = "Y";
			}
		}

		if(!$USER->IsAuthorized()){
			if($OPTION_REGISTER == "Y"){
				$arResult = $USER->SimpleRegister($_GET["email"]);
				if($arResult["TYPE"] == "ERROR"){
					exit(
						jsonEn(
							array(
								"ERROR" => $arResult["MESSAGE"]
							)
						)
					);
				}
				//else{
					// CUser::SendUserInfo($USER->GetID(), $_GET["SITE_ID"], GetMessage("NEW_REGISTER"));
				// }
			}else{

				$rsUser = CUser::GetByLogin("unregistered");
				$arUser = $rsUser->Fetch();
				if(!empty($arUser)){
					$USER_ID = $arUser["ID"];
				}else{

					$newUser = new CUser;
					$newPass = rand(0, 999999999);
					$arUserFields = Array(
					  "NAME"              => "unregistered",
					  "LAST_NAME"         => "unregistered",
					  "EMAIL"             => "unregistered@unregistered.com",
					  "LOGIN"             => "unregistered",
					  "LID"               => "ru",
					  "ACTIVE"            => "Y",
					  "GROUP_ID"          => array(),
					  "PASSWORD"          => $newPass,
					  "CONFIRM_PASSWORD"  => $newPass,
					);
					
					$USER_ID = $newUser->Add($arUserFields);
				}
				$SAVE_FIELDS = false;
			}
		}

		if(!empty($USER_NAME) && count($USER_NAME) > 0){

			if(!empty($USER_NAME[0])){
				$fields["NAME"] = BX_UTF == true ? $USER_NAME[0] : iconv("UTF-8","windows-1251//IGNORE", $USER_NAME[0]);
			}
			
			if(!empty($USER_NAME[1])){
				$fields["LAST_NAME"] = BX_UTF == true ? $USER_NAME[1] : iconv("UTF-8","windows-1251//IGNORE", $USER_NAME[1]);
			}

			if(!empty($USER_NAME[2])){
				$fields["SECOND_NAME"] = BX_UTF == true ? $USER_NAME[2] : iconv("UTF-8","windows-1251//IGNORE", $USER_NAME[2]);
			}

		}

		if(!empty($PERSONAL_MOBILE)){
			$fields["PERSONAL_MOBILE"] = BX_UTF == true ? $PERSONAL_MOBILE : iconv("UTF-8","windows-1251//IGNORE", $PERSONAL_MOBILE);
		}

		if(!empty($PERSONAL_ADDRESS)){
			$fields["PERSONAL_STREET"] = BX_UTF == true ? $PERSONAL_ADDRESS : iconv("UTF-8","windows-1251//IGNORE", $PERSONAL_ADDRESS);
		}
		
		$user = new CUser;
		$user->Update($USER->GetID(), $fields);

		$ORDER_PRICE    = 0;
		$ORDER_QUANTITY = 0;
		$ORDER_DISCOUNT = 0;
		$ORDER_WEIGHT   = 0;
		$ORDER_MESSAGE  = "<tr><td>".GetMessage("TOP_NAME")."</td><td>".GetMessage("TOP_QTY")."</td><td>".GetMessage("PRICE")."</td></tr>";



		CSaleBasket::UpdateBasketPrices(CSaleBasket::GetBasketUserID(), $SITE_ID);

		$arID = array();
		$arBasketItems = array();
		$arBasketOrder = array("NAME" => "ASC", "ID" => "ASC");
		$arBasketUser = array("FUSER_ID" => CSaleBasket::GetBasketUserID(), "LID" => $SITE_ID, "ORDER_ID" => "NULL");
		$arBasketSelect = array("ID", "CALLBACK_FUNC", "MODULE", "PRODUCT_ID", "QUANTITY", "DELAY",
				"CAN_BUY", "PRICE", "WEIGHT", "NAME", "CURRENCY", "CATALOG_XML_ID", "VAT_RATE",
				"NOTES", "DISCOUNT_PRICE", "PRODUCT_PROVIDER_CLASS", "DIMENSIONS", "TYPE", "SET_PARENT_ID", "DETAIL_PAGE_URL", "*"
		);
		$dbBasketItems = CSaleBasket::GetList($arBasketOrder, $arBasketUser, false, false, $arBasketSelect);

		$arResult["SUM"]          = 0;
		$arResult["ORDER_WEIGHT"] = 0;
		$arResult["SUM_DELIVERY"] = 0;

		$arResult["MAX_DIMENSIONS"] = array();
		$arResult["ITEMS_DIMENSIONS"] = array();

		while ($arItems = $dbBasketItems->Fetch()){

			CSaleBasket::UpdatePrice(
				$arItems["ID"],
				$arItems["CALLBACK_FUNC"],
				$arItems["MODULE"],
				$arItems["PRODUCT_ID"],
				$arItems["QUANTITY"],
				"N",
				$arItems["PRODUCT_PROVIDER_CLASS"]
			);

			array_push($arID, $arItems["ID"]);

			$arDim = $arItems["DIMENSIONS"] = $arItems["~DIMENSIONS"];

			if(is_array($arDim)){
				$arResult["MAX_DIMENSIONS"] = CSaleDeliveryHelper::getMaxDimensions(
					array(
						$arDim["WIDTH"],
						$arDim["HEIGHT"],
						$arDim["LENGTH"]
						),
					$arResult["MAX_DIMENSIONS"]);

				$arResult["ITEMS_DIMENSIONS"][] = $arDim;
			}

		}

		if (!empty($arID)){

			$dbBasketItems = CSaleBasket::GetList(
				$arBasketOrder,
				array(
					"ID" => $arID,
					"ORDER_ID" => "NULL"
				),
				false,
				false,
				$arBasketSelect
			);

			if($dbBasketItems->SelectedRowsCount() <= 0){
				exit(
					jsonEn(
						array(
							"ERROR" => GetMessage("ORDER_EMPTY")
						)
					)
				);
			}

			while ($arItems = $dbBasketItems->Fetch()){
			    $arResult["SUM"]    += ($arItems["PRICE"]  * $arItems["QUANTITY"]);
			    $arResult["ORDER_WEIGHT"] += ($arItems["WEIGHT"] * $arItems["QUANTITY"]);
			    $arResult["ITEMS"][$arItems["PRODUCT_ID"]] = $arItems;
			    $arResult["ID"][] = $arItems["PRODUCT_ID"];
			}
		 
			$arOrder = array(
				"SITE_ID" => $SITE_ID,
				"USER_ID" => $USER_ID,
				"ORDER_PRICE" => $arResult["SUM"],
				"ORDER_WEIGHT" => $arResult["ORDER_WEIGHT"],
				"BASKET_ITEMS" => $arResult["ITEMS"],
				"PERSON_TYPE_ID" => intval($_GET["PERSON_TYPE"]),
				"PAY_SYSTEM_ID" => intval($_GET["PAY_TYPE"]),
				//"DELIVERY_ID" => $DELIVERY_ID
			);

			$arOptions = array(
				"COUNT_DISCOUNT_4_ALL_QUANTITY" => "Y",
			);

			$arErrors = array();

			CSaleDiscount::DoProcessOrder($arOrder, $arOptions, $arErrors);

			foreach ($arOrder["BASKET_ITEMS"] as $arItem){

				$ORDER_DISCOUNT  += ($arItem["QUANTITY"] * $arItem["DISCOUNT_PRICE"]);
				$ORDER_WEIGHT    += ($arItem["WEIGHT"] * $arItem["QUANTITY"]);
				$ORDER_PRICE     += ($arItem["PRICE"] * $arItem["QUANTITY"]);
				$ORDER_QUANTITY  += $arItem["QUANTITY"];
				$ORDER_MESSAGE   .= "<tr><td>".$arItem["NAME"]."</td><td>".$arItem["QUANTITY"]."</td><td>".SaleFormatCurrency($arItem["PRICE"], $arItem["CURRENCY"])." ".$arItem["CURRENCY"]."</td></tr>";

				if (!CSaleBasketHelper::isSetItem($arItem))
					$arResult["BASKET_ITEMS"][$arRes["ID"]] = $arItem;

				$arDim = $arItem["DIMENSIONS"] = $arItem["~DIMENSIONS"];

				if(is_array($arDim)){
					$arResult["MAX_DIMENSIONS"] = CSaleDeliveryHelper::getMaxDimensions(
					array(
						$arItem["WIDTH"],
						$arItem["HEIGHT"],
						$arItem["LENGTH"]
					),
					$arResult["MAX_DIMENSIONS"]);

					$arResult["ITEMS_DIMENSIONS"][] = $arDim;
				}

			}
		}

		if(!empty($_GET["location"])){
			
			$dbLoc = CSaleLocation::GetList(array(), array("ID" => $_GET["location"]), false, false, array("*"));
			if($arLoc = $dbLoc->Fetch()){
				$arResult["LOCATION"] = $arLoc;
				$arUserResult["DELIVERY_LOCATION_ID"] = $arLoc["ID"];
				$arUserResult["DELIVERY_LOCATION"] = $arLoc["CODE"];			}

			$arLocs = CSaleLocation::GetLocationZIP($arUserResult["DELIVERY_LOCATION_ID"]); 
			if(!empty($arLocs)){
				$arLocs = $arLocs->Fetch();
			}

			$locFrom = COption::GetOptionString("sale", "location", false, $SITE_ID);

			$arFilter = array(
				"COMPABILITY" => array(
					"WEIGHT" => $ORDER_WEIGHT,
					"PRICE" => $ORDER_PRICE,
					"LOCATION_FROM" => $locFrom,
					"LOCATION_TO" => $arUserResult["DELIVERY_LOCATION"],
					"LOCATION_ZIP" => !empty($arLocs["ZIP"]) ? $arLocs["ZIP"] : false,
					"MAX_DIMENSIONS" => $arResult["MAX_DIMENSIONS"],
					"ITEMS" => $arResult["BASKET_ITEMS"]
				)
			);

						$arUserResult["PAY_SYSTEM_ID"] = IntVal($arUserResult["PAY_SYSTEM_ID"]);
			$arUserResult["DELIVERY_ID"] = trim($arUserResult["DELIVERY_ID"]);
			$bShowDefaultSelected = True;
			$arD2P = array();
			$arP2D = array();
			$delivery = "";
			$bSelected = false;

			$dbRes = CSaleDelivery::GetDelivery2PaySystem(array());
			while ($arRes = $dbRes->Fetch())
			{
				$arD2P[$arRes["DELIVERY_ID"]][$arRes["PAYSYSTEM_ID"]] = $arRes["PAYSYSTEM_ID"];
				$arP2D[$arRes["PAYSYSTEM_ID"]][$arRes["DELIVERY_ID"]] = $arRes["DELIVERY_ID"];
				$bShowDefaultSelected = False;
			}

			if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "d2p")
				$arP2D = array();

			if ($arParams["DELIVERY_TO_PAYSYSTEM"] == "p2d")
			{
				if(IntVal($arUserResult["PAY_SYSTEM_ID"]) <= 0)
				{
					$bFirst = True;
					$arFilter = array(
						"ACTIVE" => "Y",
						"PERSON_TYPE_ID" => $arUserResult["PERSON_TYPE_ID"],
						"PSA_HAVE_PAYMENT" => "Y"
					);
					$dbPaySystem = CSalePaySystem::GetList(
								array("SORT" => "ASC", "PSA_NAME" => "ASC"),
								$arFilter
						);
					while ($arPaySystem = $dbPaySystem->Fetch())
					{
						if (IntVal($arUserResult["PAY_SYSTEM_ID"]) <= 0 && $bFirst)
						{
							$arPaySystem["CHECKED"] = "Y";
							$arUserResult["PAY_SYSTEM_ID"] = $arPaySystem["ID"];
						}
						$bFirst = false;
					}
				}
			}

			$bFirst = True;
			$bFound = false;
			$_SESSION["SALE_DELIVERY_EXTRA_PARAMS"] = array(); // here we will store params for params dialog

			//select calc delivery
			foreach($arDeliveryServiceAll as $arDeliveryService)
			{
				foreach ($arDeliveryService["PROFILES"] as $profile_id => $arDeliveryProfile)
				{
					if ($arDeliveryProfile["ACTIVE"] == "Y"
							&& (count($arP2D[$arUserResult["PAY_SYSTEM_ID"]]) <= 0
							|| in_array($arDeliveryService["SID"], $arP2D[$arUserResult["PAY_SYSTEM_ID"]])
							|| empty($arD2P[$arDeliveryService["SID"]])
							))
					{
						$delivery_id = $arDeliveryService["SID"];
						$arProfile = array(
							"SID" => $profile_id,
							"TITLE" => $arDeliveryProfile["TITLE"],
							"DESCRIPTION" => $arDeliveryProfile["DESCRIPTION"],
							"FIELD_NAME" => "DELIVERY_ID",
						);


						if((strlen($arUserResult["DELIVERY_ID"]) > 0 && $arUserResult["DELIVERY_ID"] == $delivery_id.":".$profile_id))
						{
							$arProfile["CHECKED"] = "Y";
							$arUserResult["DELIVERY_ID"] = $delivery_id.":".$profile_id;
							$bSelected = true;

							$arOrderTmpDel = array(
								"PRICE" => $arResult["ORDER_PRICE"],
								"WEIGHT" => $arResult["ORDER_WEIGHT"],
								"DIMENSIONS" => $arResult["ORDER_DIMENSIONS"],
								"LOCATION_FROM" => COption::GetOptionString('sale', 'location'),
								"LOCATION_TO" => $arUserResult["DELIVERY_LOCATION"],
								"LOCATION_ZIP" => $arUserResult["DELIVERY_LOCATION_ZIP"],
								"ITEMS" => $arResult["BASKET_ITEMS"],
								"EXTRA_PARAMS" => $arResult["DELIVERY_EXTRA"]
							);

							$arDeliveryPrice = CSaleDeliveryHandler::CalculateFull($delivery_id, $profile_id, $arOrderTmpDel, $arResult["BASE_LANG_CURRENCY"]);

							if ($arDeliveryPrice["RESULT"] == "ERROR")
							{
								$arResult["ERROR"][] = $arDeliveryPrice["TEXT"];
							}
							else
							{
								$arResult["DELIVERY_PRICE"] = roundEx($arDeliveryPrice["VALUE"], SALE_VALUE_PRECISION);
								$arResult["PACKS_COUNT"] = $arDeliveryPrice["PACKS_COUNT"];
							}
						}

						if (empty($arResult["DELIVERY"][$delivery_id]))
						{
							$arResult["DELIVERY"][$delivery_id] = array(
								"SID" => $delivery_id,
								"SORT" => $arDeliveryService["SORT"],
								"TITLE" => $arDeliveryService["NAME"],
								"DESCRIPTION" => $arDeliveryService["DESCRIPTION"],
								"PROFILES" => array(),
							);
						}

						$arDeliveryExtraParams = CSaleDeliveryHandler::GetHandlerExtraParams($delivery_id, $profile_id, $arOrderTmpDel, SITE_ID);

						if(!empty($arDeliveryExtraParams))
						{
							$_SESSION["SALE_DELIVERY_EXTRA_PARAMS"][$delivery_id.":".$profile_id] = $arDeliveryExtraParams;
							$arResult["DELIVERY"][$delivery_id]["ISNEEDEXTRAINFO"] = "Y";
						}
						else
						{
							$arResult["DELIVERY"][$delivery_id]["ISNEEDEXTRAINFO"] = "N";
						}

						if(!empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") !== false)
						{
							if($arUserResult["DELIVERY_ID"] == $delivery_id.":".$profile_id)
								$bFound = true;
						}

						$arResult["DELIVERY"][$delivery_id]["LOGOTIP"] = $arDeliveryService["LOGOTIP"];
						$arResult["DELIVERY"][$delivery_id]["PROFILES"][$profile_id] = $arProfile;
						$bFirst = false;
					}
				}
			}
			if(!$bFound && !empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") !== false)
				$arUserResult["DELIVERY_ID"] = "";

			/*Old Delivery*/
			$arStoreId = array();
			$arDeliveryAll = array();
			$bFound = false;
			$bFirst = true;

			$dbDelivery = CSaleDelivery::GetList(
				array("SORT"=>"ASC", "NAME"=>"ASC"),
				array(
					"LID" => SITE_ID,
					"+<=WEIGHT_FROM" => $arResult["ORDER_WEIGHT"],
					"+>=WEIGHT_TO" => $arResult["ORDER_WEIGHT"],
					"+<=ORDER_PRICE_FROM" => $arResult["ORDER_PRICE"],
					"+>=ORDER_PRICE_TO" => $arResult["ORDER_PRICE"],
					"ACTIVE" => "Y",
					"LOCATION" => $arUserResult["DELIVERY_LOCATION"],
				)
			);
			while ($arDelivery = $dbDelivery->Fetch())
			{
				$arStore = array();
				if (strlen($arDelivery["STORE"]) > 0)
				{
					$arStore = unserialize($arDelivery["STORE"]);
					foreach ($arStore as $val)
						$arStoreId[$val] = $val;
				}

				$arDelivery["STORE"] = $arStore;

				if (isset($_POST["BUYER_STORE"]) && in_array($_POST["BUYER_STORE"], $arStore))
				{
					$arUserResult['DELIVERY_STORE'] = $arDelivery["ID"];
				}

				$arDeliveryDescription = CSaleDelivery::GetByID($arDelivery["ID"]);
				$arDelivery["DESCRIPTION"] = htmlspecialcharsbx($arDeliveryDescription["DESCRIPTION"]);

				$arDeliveryAll[] = $arDelivery;

				if(!empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") === false)
				{
					if(IntVal($arUserResult["DELIVERY_ID"]) == IntVal($arDelivery["ID"]))
						$bFound = true;
				}
				if(IntVal($arUserResult["DELIVERY_ID"]) == IntVal($arDelivery["ID"]))
				{
					$arResult["DELIVERY_PRICE"] = roundEx(CCurrencyRates::ConvertCurrency($arDelivery["PRICE"], $arDelivery["CURRENCY"], $arResult["BASE_LANG_CURRENCY"]), SALE_VALUE_PRECISION);
				}
			}
			if(!$bFound && !empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") === false)
			{
				$arUserResult["DELIVERY_ID"] = "";
			}

			$arStore = array();
			$dbList = CCatalogStore::GetList(
				array("SORT" => "DESC", "ID" => "DESC"),
				array("ACTIVE" => "Y", "ID" => $arStoreId, "ISSUING_CENTER" => "Y", "+SITE_ID" => SITE_ID),
				false,
				false,
				array("ID", "TITLE", "ADDRESS", "DESCRIPTION", "IMAGE_ID", "PHONE", "SCHEDULE", "GPS_N", "GPS_S", "ISSUING_CENTER", "SITE_ID")
			);
			while ($arStoreTmp = $dbList->Fetch())
			{
				if ($arStoreTmp["IMAGE_ID"] > 0)
					$arStoreTmp["IMAGE_ID"] = CFile::GetFileArray($arStoreTmp["IMAGE_ID"]);

				$arStore[$arStoreTmp["ID"]] = $arStoreTmp;
			}

			$arResult["STORE_LIST"] = $arStore;

			if(!$bFound && !empty($arUserResult["DELIVERY_ID"]) && strpos($arUserResult["DELIVERY_ID"], ":") === false)
				$arUserResult["DELIVERY_ID"] = "";

			foreach($arDeliveryAll as $arDelivery)
			{
				if (count($arP2D[$arUserResult["PAY_SYSTEM_ID"]]) <= 0 || in_array($arDelivery["ID"], $arP2D[$arUserResult["PAY_SYSTEM_ID"]]))
				{
					$arDelivery["FIELD_NAME"] = "DELIVERY_ID";
					if ((IntVal($arUserResult["DELIVERY_ID"]) == IntVal($arDelivery["ID"])))
					{
						$arDelivery["CHECKED"] = "Y";
						$arUserResult["DELIVERY_ID"] = $arDelivery["ID"];
						$arResult["DELIVERY_PRICE"] = roundEx(CCurrencyRates::ConvertCurrency($arDelivery["PRICE"], $arDelivery["CURRENCY"], $arResult["BASE_LANG_CURRENCY"]), SALE_VALUE_PRECISION);
						$bSelected = true;
					}
					if (IntVal($arDelivery["PERIOD_FROM"]) > 0 || IntVal($arDelivery["PERIOD_TO"]) > 0)
					{
						$arDelivery["PERIOD_TEXT"] = GetMessage("SALE_DELIV_PERIOD");
						if (IntVal($arDelivery["PERIOD_FROM"]) > 0)
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_FROM")." ".IntVal($arDelivery["PERIOD_FROM"]);
						if (IntVal($arDelivery["PERIOD_TO"]) > 0)
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_TO")." ".IntVal($arDelivery["PERIOD_TO"]);
						if ($arDelivery["PERIOD_TYPE"] == "H")
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_HOUR")." ";
						elseif ($arDelivery["PERIOD_TYPE"]=="M")
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_MONTH")." ";
						else
							$arDelivery["PERIOD_TEXT"] .= " ".GetMessage("SOA_DAY")." ";
					}

					if (intval($arDelivery["LOGOTIP"]) > 0)
						$arDelivery["LOGOTIP"] = CFile::GetFileArray($arDelivery["LOGOTIP"]);

					$arDelivery["PRICE_FORMATED"] = SaleFormatCurrency($arDelivery["PRICE"], $arDelivery["CURRENCY"]);
					$arResult["DELIVERY"][$arDelivery["ID"]] = $arDelivery;
					$bFirst = false;
				}
			}

			uasort($arResult["DELIVERY"], array('CSaleBasketHelper', 'cmpBySort')); // resort delivery arrays according to SORT value

			if(!$bSelected && !empty($arResult["DELIVERY"]))
			{
				$bf = true;
				foreach($arResult["DELIVERY"] as $k => $v)
				{
					if($bf)
					{
						if(IntVal($k) > 0)
						{
							$arResult["DELIVERY"][$k]["CHECKED"] = "Y";
							$arUserResult["DELIVERY_ID"] = $k;
							$bf = false;

							$arResult["DELIVERY_PRICE"] = roundEx(CCurrencyRates::ConvertCurrency($arResult["DELIVERY"][$k]["PRICE"], $arResult["DELIVERY"][$k]["CURRENCY"], $arResult["BASE_LANG_CURRENCY"]), SALE_VALUE_PRECISION);
						}
						else
						{
							foreach($v["PROFILES"] as $kk => $vv)
							{
								if($bf)
								{
									$arResult["DELIVERY"][$k]["PROFILES"][$kk]["CHECKED"] = "Y";
									$arUserResult["DELIVERY_ID"] = $k.":".$kk;
									$bf = false;

									$arOrderTmpDel = array(
										"PRICE" => $arResult["ORDER_PRICE"],
										"WEIGHT" => $arResult["ORDER_WEIGHT"],
										"DIMENSIONS" => $arResult["ORDER_DIMENSIONS"],
										"LOCATION_FROM" => COption::GetOptionString('sale', 'location'),
										"LOCATION_TO" => $arUserResult["DELIVERY_LOCATION"],
										"LOCATION_ZIP" => $arUserResult["DELIVERY_LOCATION_ZIP"],
										"ITEMS" => $arResult["BASKET_ITEMS"],
										"EXTRA_PARAMS" => $arResult["DELIVERY_EXTRA"]
									);

									$arDeliveryPrice = CSaleDeliveryHandler::CalculateFull($k, $kk, $arOrderTmpDel, $arResult["BASE_LANG_CURRENCY"]);

									if ($arDeliveryPrice["RESULT"] == "ERROR")
									{
										$arResult["ERROR"][] = $arDeliveryPrice["TEXT"];
									}
									else
									{
										$arResult["DELIVERY_PRICE"] = roundEx($arDeliveryPrice["VALUE"], SALE_VALUE_PRECISION);
										$arResult["PACKS_COUNT"] = $arDeliveryPrice["PACKS_COUNT"];
									}
									break;
								}
							}
						}
					}
				}
			}

			if ($arUserResult["PAY_SYSTEM_ID"] > 0 || strlen($arUserResult["DELIVERY_ID"]) > 0)
			{
				if (strlen($arUserResult["DELIVERY_ID"]) > 0 && $arParams["DELIVERY_TO_PAYSYSTEM"] == "d2p")
				{
					if (strpos($arUserResult["DELIVERY_ID"], ":"))
					{
						$tmp = explode(":", $arUserResult["DELIVERY_ID"]);
						$delivery = trim($tmp[0]);
					}
					else
						$delivery = intval($arUserResult["DELIVERY_ID"]);
				}
			}

			if(DoubleVal($arResult["DELIVERY_PRICE"]) > 0)
				$arResult["DELIVERY_PRICE_FORMATED"] = SaleFormatCurrency($arResult["DELIVERY_PRICE"], $arResult["BASE_LANG_CURRENCY"]);

			foreach(GetModuleEvents("sale", "OnSaleComponentOrderOneStepDelivery", true) as $arEvent)
				ExecuteModuleEventEx($arEvent, array(&$arResult, &$arUserResult, &$arParams));

		}else{
			exit(
				jsonEn(
					array(
						"ERROR" => "Delivery error (3); Check field IS_LOCATION!!!."
					)
				)
			);
		}

		$DELIVERY_INFO = $arResult["DELIVERY"][$DELIVERY_ID];

		if(!empty($DELIVERY_INFO)){

			foreach ($_GET as $i => $prop_value) {
				if(strstr($i, "ORDER_PROP")){

					$nextProp = CSaleOrderProps::GetByID(
						preg_replace('/[^0-9]/', '', $i)
					);

					if($nextProp["IS_LOCATION"] == "Y"){
						$prop_value = $_GET["location"];
					}

					$arResult["ORDER_PROP"][$nextProp["ID"]] = (BX_UTF == 1) ? $prop_value : iconv("UTF-8", "windows-1251//IGNORE", $prop_value);

				}
			}

			$arOrderDat = CSaleOrder::DoCalculateOrder(
				$SITE_ID,
				!empty($USER_ID) ? $USER_ID : IntVal($USER->GetID()),
				$arResult["BASKET_ITEMS"],
				$_GET["PERSON_TYPE"],
				$arResult["ORDER_PROP"],
				$DELIVERY_CODE,
				$_GET["PAY_TYPE"],
				array(),
				$arErrors,
				$arWarnings
			);
			
			$arOrderFields = array(
			   "LID" => $SITE_ID,
			   "PERSON_TYPE_ID" => $_GET["PERSON_TYPE"],
			   "PAYED" => "N",
			   "CANCELED" => "N",
			   "STATUS_ID" => "N",
			   "PRICE" => ($DELIVERY_INFO["PRICE"] + $ORDER_PRICE),
			   "CURRENCY" => $OPTION_CURRENCY,
			   "USER_ID" => !empty($USER_ID) ? $USER_ID : IntVal($USER->GetID()),
			   "PAY_SYSTEM_ID" => $_GET["PAY_TYPE"],
			   "PRICE_DELIVERY" => $DELIVERY_INFO["PRICE"],
			   "DELIVERY_ID" => $DELIVERY_CODE,
			   "DISCOUNT_VALUE" => $ORDER_DISCOUNT,
			   "TAX_VALUE" => 0.0,
			   "USER_DESCRIPTION" => (BX_UTF == 1) ? $_GET["COMMENT"] : iconv("UTF-8", "windows-1251//IGNORE", $_GET["COMMENT"])
			);
			
			$ORDER_ID = (int)CSaleOrder::DoSaveOrder($arOrderDat, $arOrderFields, 0, $arResult["ERROR"]);
			
			if(!empty($arResult["ERROR"])){
				exit(
					jsonEn(
						array(
							"ERROR" => $arResult["ERROR"]
						)
					)
				);	
			}

			if(empty($ORDER_ID)){
				exit(
					jsonEn(
						array(
							"ERROR" => GetMessage("ORDER_ERROR")
						)
					)
				);
			}

			$orderInfo = CSaleOrder::GetByID($ORDER_ID);
	
			CSaleBasket::OrderBasket(
				intval($ORDER_ID), intval($_SESSION["SALE_USER_ID"]), $SITE_ID, false
			);


			$PAYSYSTEM = CSalePaySystem::GetByID(
				$_GET["PAY_TYPE"],
				$_GET["PERSON_TYPE"]
			);
			
			$res = CSalePaySystemAction::GetList(
				array(),
				array(
						"PAY_SYSTEM_ID" => $PAYSYSTEM["ID"],
						"PERSON_TYPE_ID" => $_GET["PERSON_TYPE"]
					),
				false,
				false,
				array("ID", "PAY_SYSTEM_ID", "PERSON_TYPE_ID", "NAME", "ACTION_FILE", "RESULT_FILE", "NEW_WINDOW", "PARAMS", "ENCODING", "LOGOTIP")
			);

			if ($PAYSYSTEM_ACTION = $res->Fetch()){
				$dbOrder = CSaleOrder::GetList(
					array("DATE_UPDATE" => "DESC"),
					array(
						"LID" => $SITE_ID,
						"ID" => $ORDER_ID
					)
				);
				if($arOrder = $dbOrder->GetNext()){
					CSalePaySystemAction::InitParamArrays($arOrder, $arOrder["ID"], $PAYSYSTEM_ACTION["PARAMS"]);
					$PAY_DATA = returnBuff($_SERVER["DOCUMENT_ROOT"].$PAYSYSTEM_ACTION["ACTION_FILE"]."/payment.php");
					echo jsonEn(
						array(
							"ORDER_ID" => $orderInfo["ACCOUNT_NUMBER"],
							"NEW_WINDOW" => $PAYSYSTEM_ACTION["NEW_WINDOW"],
							"PAYSYSTEM" => trim(
								str_replace(
									array("\n", "\r", "\t"), "", $PAY_DATA)
							)
						)
					);
				}
			}
		
			$arFields = Array(
				"ORDER_ID" => $orderInfo["ACCOUNT_NUMBER"],
				"ORDER_DATE" => Date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT", $SITE_ID))),
				"ORDER_USER" => $USER->GetFormattedName(false),
				"PRICE" => SaleFormatCurrency($ORDER_PRICE, $OPTION_CURRENCY),
				"BCC" => COption::GetOptionString("sale", "order_email", "order@".$SERVER_NAME),
				"EMAIL" => !empty($_GET["email"]) ? $_GET["email"] : $USER->GetEmail(),
				"ORDER_LIST" => "<table width=100%>".$ORDER_MESSAGE."</table>",
				"SALE_EMAIL" => COption::GetOptionString("sale", "order_email", "order@".$SERVER_NAME),
				"DELIVERY_PRICE" => $DELIVERY_INFO["PRICE"],
			);

			$eventName = "SALE_NEW_ORDER";

			$bSend = true;
			foreach(GetModuleEvents("sale", "OnOrderNewSendEmail", true) as $arEvent)
				if (ExecuteModuleEventEx($arEvent, Array($ORDER_ID, &$eventName, &$arFields))===false)
					$bSend = false;

			if($bSend){
				$event = new CEvent;
				$event->Send($eventName, $SITE_ID, $arFields, Y);
			}

			// CSaleMobileOrderPush::send("ORDER_CREATED", array("ORDER_ID" => $arFields["ORDER_ID"]));


		}else{
			exit(
				jsonEn(
					array(
						"ERROR" => "Delivery error (4); Check logo delivery system please."
					)
				)
			);
		}

	}
}else{
	die(false);
}

function jsonEn($data){
	foreach ($data as $index => $arValue) {
		$arJsn[] = '"'.$index.'" : "'.addslashes($arValue).'"';
	}
	return  "{".implode($arJsn, ",")."}";
}

function jsonMultiEn($data){
	if(is_array($data)){
		if(count($data) > 0){
			$arJsn = "[".implode(getJnLevel($data, 0), ",")."]";
		}else{
			$arJsn = implode(getJnLevel($data), ",");
		}
	}
	return str_replace(array("\t", "\r", "\n"), "", $arJsn);
}

function getJnLevel($data = array(), $level = 1, $arJsn = array()){
	if(!empty($data)){
		foreach ($data as $i => $arNext) {
			if(!is_array($arNext)){
				$arJsn[] = '"'.$i.'":"'.addslashes($arNext).'"';
			}else{
				if($level === 0){
					$arJsn[] = "{".implode(getJnLevel($arNext), ",")."}";
				}else{
					$arJsn[] = '"'.$i.'":{'.implode(getJnLevel($arNext),",").'}';
				}
				
			}
		}
	}
	return $arJsn;
}

function returnBuff($file){
	ob_start();
	include($file);
	$fData = ob_get_contents();
	ob_end_clean();
	return $fData;
}

?>