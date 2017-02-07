<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Каталог товаров");
$APPLICATION->SetTitle("Каталог товаров");
?>
<? $APPLICATION->IncludeComponent(
	"bitrix:catalog",
	".default",
	array(
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "25",
		"TEMPLATE_THEME" => "site",
		"HIDE_NOT_AVAILABLE" => "L",
		"BASKET_URL" => "/personal/cart/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "Y",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "Y",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"ADD_SECTION_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"SET_STATUS_404" => "Y",
		"DETAIL_DISPLAY_NAME" => "Y",
		"USE_ELEMENT_COUNTER" => "Y",
		"USE_FILTER" => "Y",
		"FILTER_NAME" => "",
		"FILTER_VIEW_MODE" => "VERTICAL",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PRICE_CODE" => array(
			0 => "RITAIL",
		),
		"FILTER_OFFERS_FIELD_CODE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DETAIL_PICTURE",
			2 => "",
		),
		"FILTER_OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR",
			1 => "MATERIAL",
			2 => "SIZE_CLOTHES",
			3 => "",
		),
		"USE_REVIEW" => "Y",
		"MESSAGES_PER_PAGE" => "10",
		"USE_CAPTCHA" => "Y",
		"REVIEW_AJAX_POST" => "Y",
		"PATH_TO_SMILE" => "/bitrix/images/forum/smile/",
		"FORUM_ID" => "",
		"URL_TEMPLATES_READ" => "",
		"SHOW_LINK_TO_FORUM" => "N",
		"USE_COMPARE" => "Y",
		"PRICE_CODE" => array(
			0 => "RITAIL",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"USE_PRODUCT_QUANTITY" => "Y",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "RUB",
		"QUANTITY_FLOAT" => "N",
		"OFFERS_CART_PROPERTIES" => array(
		),
		"SHOW_TOP_ELEMENTS" => "N",
		"SECTION_COUNT_ELEMENTS" => "Y",
		"SECTION_TOP_DEPTH" => "4",
		"SECTIONS_VIEW_MODE" => "TEXT",
		"SECTIONS_SHOW_PARENT_NAME" => "N",
		"PAGE_ELEMENT_COUNT" => "30",
		"LINE_ELEMENT_COUNT" => "3",
		"ELEMENT_SORT_FIELD" => "CATALOG_AVAILABLE",
		"ELEMENT_SORT_ORDER" => "desc",
		"ELEMENT_SORT_FIELD2" => "SHOWS",
		"ELEMENT_SORT_ORDER2" => "desc",
		"LIST_PROPERTY_CODE" => array(
			0 => "ATT_BRAND",
			1 => "CML2_ARTICLE",
			2 => "SKU_COLOR",
			3 => "DELIVERY",
			4 => "OFFERS",
			5 => "",
		),
		"INCLUDE_SUBSECTIONS" => "Y",
		"LIST_META_KEYWORDS" => "UF_METAKEYWORDS",
		"LIST_META_DESCRIPTION" => "UF_METADESCRIPTION",
		"LIST_BROWSER_TITLE" => "UF_METATITLE",
		"LIST_OFFERS_FIELD_CODE" => array(
			0 => "ID",
			1 => "CODE",
			2 => "XML_ID",
			3 => "NAME",
			4 => "TAGS",
			5 => "SORT",
			6 => "PREVIEW_TEXT",
			7 => "PREVIEW_PICTURE",
			8 => "DETAIL_TEXT",
			9 => "DETAIL_PICTURE",
			10 => "DATE_ACTIVE_FROM",
			11 => "ACTIVE_FROM",
			12 => "DATE_ACTIVE_TO",
			13 => "ACTIVE_TO",
			14 => "SHOW_COUNTER",
			15 => "SHOW_COUNTER_START",
			16 => "IBLOCK_TYPE_ID",
			17 => "IBLOCK_ID",
			18 => "IBLOCK_CODE",
			19 => "IBLOCK_NAME",
			20 => "IBLOCK_EXTERNAL_ID",
			21 => "DATE_CREATE",
			22 => "CREATED_BY",
			23 => "CREATED_USER_NAME",
			24 => "TIMESTAMP_X",
			25 => "MODIFIED_BY",
			26 => "USER_NAME",
			27 => "",
		),
		"LIST_OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR",
			1 => "SIZE",
			2 => "",
		),
		"LIST_OFFERS_LIMIT" => "0",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "VIDEO",
			1 => "ATT_BRAND",
			2 => "CML2_ARTICLE",
			3 => "OPERATING_SYSTEMS",
			4 => "CAMERA_TYPE",
			5 => "MEGAPIXELS",
			6 => "WIFI",
			7 => "ONVIF_SUPPORT",
			8 => "SKU_COLOR",
			9 => "SIZE",
			10 => "INDOOR_OUTDOOR",
			11 => "FORM_FACTOR",
			12 => "TEMPERATURA",
			13 => "IP_CLASS",
			14 => "MATRIX_TYPE",
			15 => "LIGHT_SENSITIVITY",
			16 => "MATRIX_FORMAT",
			17 => "LENS",
			18 => "FOCUS",
			19 => "LENS_UNGLE",
			20 => "DAY_NIGHT",
			21 => "IR_LUZE",
			22 => "IR_DISTANCE",
			23 => "MEC_IR_FILTER",
			24 => "VIDEO_STREAM_COUNT",
			25 => "RES_25FPS",
			26 => "RES_FPS",
			27 => "COMPR_FORMAT",
			28 => "COLORITY",
			29 => "BUILD_IN_AUDIO",
			30 => "PoE",
			31 => "POWER",
			32 => "POE_SUPPORT",
			33 => "NET_PROTOCOLS",
			34 => "NETWORK_INTERFACE",
			35 => "SOTBIT_YM_ID",
			36 => "FLASH_CARD_SUPPORT",
			37 => "ALARM_IO",
			38 => "WARRANTY",
			39 => "MORE_INFO",
			40 => "COMPLECT",
			41 => "GPS",
			42 => "WI_FI",
			43 => "AVTOPILOT",
			44 => "AKKUMULYATOR",
			45 => "VES",
			46 => "VES_KVADROKOPTERA",
			47 => "VID_OT_PERVOGO_LITSA_FPV",
			48 => "VIDEOVYKHOD_NA_PULTE_UPRAVLENIYA",
			49 => "VOZVRASHCHENIE_V_TOCHKU_VZLETA",
			50 => "VSTROENNYE_DATCHIKI",
			51 => "GABARITY_DKHSHKHV",
			52 => "DALNOST_TRANSLYATSII_VIDEO_ILI_FOTO",
			53 => "DALNOST_UPRAVLENIYA",
			54 => "DIAMETR_VINTA",
			55 => "DISTANTSIONNOE_UPRAVLENIE_POLOZHENIEM_KAMERY",
			56 => "DOPOLNITELNAYA_INFORMATSIYA",
			57 => "KAMERA",
			58 => "KOLICHESTVO_VINTOV",
			59 => "KOMPLEKTATSIYA",
			60 => "MAKSIMALNAYA_VYSOTA_POLETA",
			61 => "MAKSIMALNAYA_SKOROST_NABORA_VYSOTY",
			62 => "MAKSIMALNAYA_SKOROST_POLETA",
			63 => "MAKSIMALNAYA_SKOROST_SNIZHENIYA",
			64 => "MAKSIMALNOE_VREMYA_POLETA",
			65 => "MAKSIMALNYY_PODEMNYY_VES",
			66 => "PODDERZHKA_MOBILNYKH_OS",
			67 => "RESOLUTION_HDTV",
			68 => "RAZRESHENIE_VIDEOSEMKI",
			69 => "RAZRESHENIE_MATRITSY",
			70 => "RAZRESHENIE_FOTOSEMKI",
			71 => "SLEDOVANIE_ZA_OPERATOROM",
			72 => "SOVMESTIMYE_FOTOKAMERY",
			73 => "TIP_MULTIKOPTERA",
			74 => "TIP_PITANIYA_PULTA_UPRAVLENIYA",
			75 => "TIP_UPRAVLENIYA",
			76 => "UGOL_OBZORA_KAMERY",
			77 => "URL_YM",
			78 => "COLLECTION",
			79 => "TOTAL_OUTPUT_POWER",
			80 => "DELIVERY",
			81 => "PICKUP",
			82 => "OFFERS",
			83 => "USER_ID",
			84 => "BLOG_POST_ID",
			85 => "BLOG_COMMENTS_CNT",
			86 => "VOTE_COUNT",
			87 => "BULK",
			88 => "SIMILAR_PRODUCT",
			89 => "RATING",
			90 => "RELATED_PRODUCT",
			91 => "",
		),
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_BROWSER_TITLE" => "NAME",
		"DETAIL_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "",
		),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(
			0 => "COLOR",
			1 => "SIZE",
			2 => "",
		),
		"LINK_IBLOCK_TYPE" => "",
		"LINK_IBLOCK_ID" => "",
		"LINK_PROPERTY_SID" => "",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"USE_ALSO_BUY" => "N",
		"ALSO_BUY_ELEMENT_COUNT" => "4",
		"ALSO_BUY_MIN_BUYES" => "1",
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"PAGER_TEMPLATE" => "round",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
		"PAGER_SHOW_ALL" => "N",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"LABEL_PROP" => "-",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR",
			1 => "SIZE_CLOTHES",
			2 => "MATERIAL",
		),
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_COMPARE" => "Сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"DETAIL_USE_VOTE_RATING" => "Y",
		"DETAIL_VOTE_DISPLAY_AS_RATING" => "rating",
		"DETAIL_USE_COMMENTS" => "Y",
		"DETAIL_BLOG_USE" => "Y",
		"DETAIL_VK_USE" => "N",
		"DETAIL_FB_USE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"USE_STORE" => "N",
		"USE_STORE_PHONE" => "Y",
		"USE_STORE_SCHEDULE" => "Y",
		"USE_MIN_AMOUNT" => "N",
		"STORE_PATH" => "/store/#store_id#",
		"MAIN_TITLE" => "Наличие на складах",
		"MIN_AMOUNT" => "10",
		"DETAIL_BRAND_USE" => "Y",
		"DETAIL_BRAND_PROP_CODE" => array(
			0 => "",
			1 => "BRAND_REF",
			2 => "",
		),
		"ADD_SECTIONS_CHAIN" => "Y",
		"COMMON_SHOW_CLOSE_POPUP" => "N",
		"DETAIL_SHOW_MAX_QUANTITY" => "N",
		"DETAIL_BLOG_URL" => "catalog_comments",
		"DETAIL_BLOG_EMAIL_NOTIFY" => "N",
		"DETAIL_FB_APP_ID" => "",
		"USE_SALE_BESTSELLERS" => "Y",
		"ADD_PROPERTIES_TO_BASKET" => "N",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
		"TOP_ADD_TO_BASKET_ACTION" => "ADD",
		"SECTION_ADD_TO_BASKET_ACTION" => "ADD",
		"DETAIL_ADD_TO_BASKET_ACTION" => array(
			0 => "BUY",
		),
		"DETAIL_SHOW_BASIS_PRICE" => "Y",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"DETAIL_DETAIL_PICTURE_MODE" => "IMG",
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "N",
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
		"STORES" => array(
			0 => "1",
		),
		"USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"FIELDS" => array(
			0 => "TITLE",
			1 => "ADDRESS",
			2 => "DESCRIPTION",
			3 => "PHONE",
			4 => "SCHEDULE",
			5 => "EMAIL",
			6 => "IMAGE_ID",
			7 => "COORDINATES",
			8 => "",
		),
		"SHOW_EMPTY_STORE" => "Y",
		"SHOW_GENERAL_STORE_INFORMATION" => "N",
		"USE_BIG_DATA" => "Y",
		"BIG_DATA_RCM_TYPE" => "any",
		"COMMON_ADD_TO_BASKET_ACTION" => "ADD",
		"COMPONENT_TEMPLATE" => ".default",
		"USE_MAIN_ELEMENT_SECTION" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SECTION_BACKGROUND_IMAGE" => "UF_IMAGES",
		"DETAIL_SET_CANONICAL_URL" => "Y",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"SHOW_DEACTIVATED" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"SHOW_404" => "Y",
		"MESSAGE_404" => "",
		"REVIEW_IBLOCK_TYPE" => "catalog",
		"REVIEW_IBLOCK_ID" => "7",
		"DISABLE_INIT_JS_IN_COMPONENT" => "Y",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"USE_GIFTS_DETAIL" => "N",
		"USE_GIFTS_SECTION" => "N",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "N",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"FILE_404" => "",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"HIDE_AVAILABLE_TAB" => "N",
		"HIDE_MEASURES" => "Y",
		"SHOW_SECTION_BANNER" => "N",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPARE_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"COMPARE_PROPERTY_CODE" => array(
			0 => "VIDEO",
			1 => "ATT_BRAND",
			2 => "CML2_ARTICLE",
			3 => "OPERATING_SYSTEMS",
			4 => "CAMERA_TYPE",
			5 => "MEGAPIXELS",
			6 => "WIFI",
			7 => "ONVIF_SUPPORT",
			8 => "SKU_COLOR",
			9 => "SIZE",
			10 => "INDOOR_OUTDOOR",
			11 => "FORM_FACTOR",
			12 => "TEMPERATURA",
			13 => "IP_CLASS",
			14 => "MATRIX_TYPE",
			15 => "LIGHT_SENSITIVITY",
			16 => "MATRIX_FORMAT",
			17 => "LENS",
			18 => "FOCUS",
			19 => "LENS_UNGLE",
			20 => "DAY_NIGHT",
			21 => "IR_LUZE",
			22 => "IR_DISTANCE",
			23 => "MEC_IR_FILTER",
			24 => "VIDEO_STREAM_COUNT",
			25 => "RES_25FPS",
			26 => "RES_FPS",
			27 => "COMPR_FORMAT",
			28 => "COLORITY",
			29 => "BUILD_IN_AUDIO",
			30 => "PoE",
			31 => "POWER",
			32 => "POE_SUPPORT",
			33 => "NET_PROTOCOLS",
			34 => "NETWORK_INTERFACE",
			35 => "SOTBIT_YM_ID",
			36 => "FLASH_CARD_SUPPORT",
			37 => "ALARM_IO",
			38 => "WARRANTY",
			39 => "MORE_INFO",
			40 => "COMPLECT",
			41 => "GPS",
			42 => "WI_FI",
			43 => "AVTOPILOT",
			44 => "AKKUMULYATOR",
			45 => "VES",
			46 => "VES_KVADROKOPTERA",
			47 => "VID_OT_PERVOGO_LITSA_FPV",
			48 => "VIDEOVYKHOD_NA_PULTE_UPRAVLENIYA",
			49 => "VOZVRASHCHENIE_V_TOCHKU_VZLETA",
			50 => "VSTROENNYE_DATCHIKI",
			51 => "GABARITY_DKHSHKHV",
			52 => "DALNOST_TRANSLYATSII_VIDEO_ILI_FOTO",
			53 => "DALNOST_UPRAVLENIYA",
			54 => "DIAMETR_VINTA",
			55 => "DISTANTSIONNOE_UPRAVLENIE_POLOZHENIEM_KAMERY",
			56 => "DOPOLNITELNAYA_INFORMATSIYA",
			57 => "KAMERA",
			58 => "KOLICHESTVO_VINTOV",
			59 => "KOMPLEKTATSIYA",
			60 => "MAKSIMALNAYA_VYSOTA_POLETA",
			61 => "MAKSIMALNAYA_SKOROST_NABORA_VYSOTY",
			62 => "MAKSIMALNAYA_SKOROST_POLETA",
			63 => "MAKSIMALNAYA_SKOROST_SNIZHENIYA",
			64 => "MAKSIMALNOE_VREMYA_POLETA",
			65 => "MAKSIMALNYY_PODEMNYY_VES",
			66 => "PODDERZHKA_MOBILNYKH_OS",
			67 => "RESOLUTION_HDTV",
			68 => "RAZRESHENIE_VIDEOSEMKI",
			69 => "RAZRESHENIE_MATRITSY",
			70 => "RAZRESHENIE_FOTOSEMKI",
			71 => "SLEDOVANIE_ZA_OPERATOROM",
			72 => "SOVMESTIMYE_FOTOKAMERY",
			73 => "TIP_MULTIKOPTERA",
			74 => "TIP_PITANIYA_PULTA_UPRAVLENIYA",
			75 => "TIP_UPRAVLENIYA",
			76 => "UGOL_OBZORA_KAMERY",
			77 => "URL_YM",
			78 => "COLLECTION",
			79 => "TOTAL_OUTPUT_POWER",
			80 => "DELIVERY",
			81 => "PICKUP",
			82 => "",
		),
		"COMPARE_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"COMPARE_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"COMPARE_ELEMENT_SORT_FIELD" => "sort",
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"element" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
			"compare" => "compare/",
			"smart_filter" => "#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/",
		)
	),
	false
); ?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>