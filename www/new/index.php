<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Новинки");?><h1>Новинки</h1><?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"personal", 
	array(
		"COMPONENT_TEMPLATE" => "personal",
		"ROOT_MENU_TYPE" => "left2",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600000",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N"
	),
	false
);?><?$APPLICATION->IncludeComponent(
	"dresscode:simple.offers", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "25",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600000",
		"PROP_NAME" => "OFFERS",
		"PROP_VALUE" => "540",
		"CONVERT_CURRENCY" => "Y",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "OFFERS",
			2 => "ATT_BRAND",
			3 => "COLOR",
			4 => "ZOOM2",
			5 => "BATTERY_LIFE",
			6 => "SWITCH",
			7 => "GRAF_PROC",
			8 => "LENGTH_OF_CORD",
			9 => "DISPLAY",
			10 => "LOADING_LAUNDRY",
			11 => "FULL_HD_VIDEO_RECORD",
			12 => "INTERFACE",
			13 => "COMPRESSORS",
			14 => "Number_of_Outlets",
			15 => "MAX_RESOLUTION_VIDEO",
			16 => "MAX_BUS_FREQUENCY",
			17 => "MAX_RESOLUTION",
			18 => "FREEZER",
			19 => "POWER_SUB",
			20 => "POWER",
			21 => "HARD_DRIVE_SPACE",
			22 => "MEMORY",
			23 => "OS",
			24 => "ZOOM",
			25 => "PAPER_FEED",
			26 => "SUPPORTED_STANDARTS",
			27 => "VIDEO_FORMAT",
			28 => "SUPPORT_2SIM",
			29 => "MP3",
			30 => "ETHERNET_PORTS",
			31 => "MATRIX",
			32 => "CAMERA",
			33 => "PHOTOSENSITIVITY",
			34 => "DEFROST",
			35 => "SPEED_WIFI",
			36 => "SPIN_SPEED",
			37 => "PRINT_SPEED",
			38 => "SOCKET",
			39 => "IMAGE_STABILIZER",
			40 => "GSM",
			41 => "SIM",
			42 => "TYPE",
			43 => "MEMORY_CARD",
			44 => "TYPE_BODY",
			45 => "TYPE_MOUSE",
			46 => "TYPE_PRINT",
			47 => "CONNECTION",
			48 => "TYPE_OF_CONTROL",
			49 => "TYPE_DISPLAY",
			50 => "TYPE2",
			51 => "REFRESH_RATE",
			52 => "RANGE",
			53 => "AMOUNT_MEMORY",
			54 => "MEMORY_CAPACITY",
			55 => "VIDEO_BRAND",
			56 => "DIAGONAL",
			57 => "RESOLUTION",
			58 => "TOUCH",
			59 => "CORES",
			60 => "LINE_PROC",
			61 => "PROCESSOR",
			62 => "CLOCK_SPEED",
			63 => "TYPE_PROCESSOR",
			64 => "PROCESSOR_SPEED",
			65 => "HARD_DRIVE",
			66 => "HARD_DRIVE_TYPE",
			67 => "Number_of_memory_slots",
			68 => "MAXIMUM_MEMORY_FREQUENCY",
			69 => "TYPE_MEMORY",
			70 => "BLUETOOTH",
			71 => "FM",
			72 => "GPS",
			73 => "HDMI",
			74 => "SMART_TV",
			75 => "USB",
			76 => "WIFI",
			77 => "FLASH",
			78 => "ROTARY_DISPLAY",
			79 => "SUPPORT_3D",
			80 => "SUPPORT_3G",
			81 => "WITH_COOLER",
			82 => "FINGERPRINT",
			83 => "COLLECTION",
			84 => "TOTAL_OUTPUT_POWER",
			85 => "HTML",
			86 => "VID_ZASTECHKI",
			87 => "VID_SUMKI",
			88 => "VIDEO",
			89 => "PROFILE",
			90 => "VYSOTA_RUCHEK",
			91 => "GAS_CONTROL",
			92 => "WARRANTY",
			93 => "GRILL",
			94 => "MORE_PROPERTIES",
			95 => "GENRE",
			96 => "OTSEKOV",
			97 => "CONVECTION",
			98 => "INTAKE_POWER",
			99 => "NAZNAZHENIE",
			100 => "BULK",
			101 => "PODKLADKA",
			102 => "SHOW_MENU",
			103 => "SURFACE_COATING",
			104 => "brand_tyres",
			105 => "SEASON",
			106 => "SEASONOST",
			107 => "DUST_COLLECTION",
			108 => "REF",
			109 => "COUNTRY_BRAND",
			110 => "DRYING",
			111 => "REMOVABLE_TOP_COVER",
			112 => "CONTROL",
			113 => "FINE_FILTER",
			114 => "FORM_FAKTOR",
			115 => "SKU_COLOR",
			116 => "USER_ID",
			117 => "BLOG_POST_ID",
			118 => "CML2_ARTICLE",
			119 => "DELIVERY",
			120 => "BLOG_COMMENTS_CNT",
			121 => "VOTE_COUNT",
			122 => "MARKER_PHOTO",
			123 => "NEW",
			124 => "DELIVERY_DESC",
			125 => "SIMILAR_PRODUCT",
			126 => "SALE",
			127 => "RATING",
			128 => "PICKUP",
			129 => "RELATED_PRODUCT",
			130 => "VOTE_SUM",
			131 => "MARKER",
			132 => "POPULAR",
			133 => "WEIGHT",
			134 => "HEIGHT",
			135 => "DEPTH",
			136 => "WIDTH",
			137 => "",
		),
		"CURRENCY_ID" => "RUB"
	),
	false,
	array(
		"ACTIVE_COMPONENT" => "Y"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>