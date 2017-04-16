<?
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_NAME"] = "Экспорт в систему авито (Работа)";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_ID"] = "Уникальный идентификатор объявления<br/>(строка не более 100 символов)<br/><b class='required'>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_DATEBEGIN"] = "Дата начала экспозиции объявления";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_DATEEND"] = "Дата конца экспозиции объявления";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_ADSTATUS"] = "Платная услуга, которую нужно применить к объявлению — одно из значений списка:<br/><br/>\"Free\" — обычное объявление;<br/>\"Premium\" — премиум-объявление;<br/>\"VIP\" — VIP-объявление;<br/>\"PushUp\" — поднятие объявления в поиске;<br/>\"Highlight\" — выделение объявления;<br/>\"TurboSale\"— применение пакета \"Турбо-продажа\";<br/>\"QuickSale\" — применение пакета \"Быстрая продажа\".";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_ALLOWEMAIL"] = "Возможность написать сообщение по объявлению через сайт — одно из значений списка: Да, Нет. Примечание: значение по умолчанию — Да.";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_MANAGERNAME"] = "Имя менеджера, контактного лица компании по данному объявлению — строка не более 40 символов.";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_CONTACTPHONE"] = "Контактный телефон — строка, содержащая только один российский номер телефона; должен быть обязательно указан код города или мобильного оператора. Корректные примеры:<br/>+7 (495) 777-10-66,<br/>(81374) 4-55-75,<br/>8 905 207 04 90,<br/>+7 905 2070490,<br/>88123855085,<br/>9052070490.";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_REGION"] = "Регион,<br/>в котором находится объект объявления<br/>в соответствии со значениями из справочника.<br/><b class='required'>Обязательный элемент</b>";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_CITY"] = "Город или населенный пункт, в котором находится объект объявления — в соответствии со значениями из справочника.<br/>
Элемент обязателен для всех регионов, кроме Москвы и Санкт-Петербурга.<br/>
Справочник является неполным. Если требуемое значение в нем отсутствует, то укажите ближайший к вашему объекту пункт из справочника, а точное название населенного пункта — в элементе Street.";

$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_STREET"] = "Адрес — строка до 65 символов, содержащая название улицы и номер дома.";

$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_DISTRICT"] = "Район города — в соответствии со значениями из справочника.";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_SUBWAY"] = "Ближайшая станция метро<br/>(в соответствии со значениями из справочника)";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_CATEGORY"] = "Категория объявлений — строка: \"Вакансии\".";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_INDUSTRY"] = "Сфера деятельности — одно из значений списка:<br/>
IT, интернет, телеком,<br/>
Автомобильный бизнес,<br/>
Административная работа,<br/>
Банки, инвестиции,<br/>
Без опыта, студенты,<br/>
Бухгалтерия, финансы,<br/>
Домашний персонал,<br/>
ЖКХ, эксплуатация,<br/>
Искусство, развлечения,<br/>
Консультирование,<br/>
Маркетинг, реклама, PR,<br/>
Медицина, фармацевтика,<br/>
Образование, наука,<br/>
Охрана, безопасность,<br/>
Продажи,<br/>
Производство, сырьё, с/х,<br/>
Строительство,<br/>
Транспорт, логистика,<br/>
Туризм, рестораны,<br/>
Управление персоналом,<br/>
Фитнес, салоны красоты,<br/>
Юриспруденция.";


$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_TITLE"] = "Название вакансии — строка до 50 символов.<br/>Примечание: не пишите в название зарплату и контактную информацию — для этого есть отдельные поля.";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_JOBTYPE"] = "График работы — одно из значений списка:<br/>
Вахтовый метод,<br/>
Неполный день,<br/>
Полный день,<br/>
Свободный график,<br/>
Сменный график,<br/>
Удалённая работа.";


$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_EXPERIENCE"] = "Опыт работы — одно из значений списка:<br/>
Не имеет значения,<br/>
Более 1 года,<br/>
Более 3 лет,<br/>
Более 5 лет,<br/>
Более 10 лет.<br/>
Примечание: не пишите в название зарплату и контактную информацию — для этого есть отдельные поля.";

$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_DESCRIPTION"] = "Текстовое описание объявления в соответствии с правилами Avito — строка не более 3000 символов.";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_SALARY"] = "Зарплата, рублей в месяц — целое число.";
$MESS["ACRIT_EXPORTPRO_AVITO_JOB_FIELD_IMAGE"] = "Изображения";
$MESS["ACRIT_EXPORTPRO_TYPE_AVITO_JOB_PORTAL_REQUIREMENTS"] = "http://autoload.avito.ru/format/job/";
$MESS["ACRIT_EXPORTPRO_TYPE_AVITO_JOB_PORTAL_VALIDATOR"] = "http://autoload.avito.ru/format/xmlcheck/";
$MESS["ACRIT_EXPORTPRO_TYPE_AVITO_JOB_EXAMPLE"] = "
<Ads formatVersion=\"3\" target=\"Avito.ru\">
    <Ad>
        <Id>723681273</Id>
        <DateBegin>2015-11-27</DateBegin>
        <DateEnd>2079-08-28</DateEnd>
        <AdStatus>TurboSale</AdStatus>
        <AllowEmail>Да</AllowEmail>
        <ManagerName>Иван Петров-Водкин</ManagerName>
        <ContactPhone>+7 916 683-78-22</ContactPhone>
        <Region>Владимирская область</Region>
        <City>Владимир</City>
        <District>Ленинский</District>
        <Street>ул. Ленина, д. 9</Street>
        <Category>Вакансии</Category>           
        <Industry>Производство, сырьё, с/х</Industry>
        <Title>Продавец-консультант</Title>
        <JobType>Полный день</JobType>
        <Experience>Более 1 года</Experience>
        <Description><![CDATA[
<p>В Компании <strong>конкурс на вакансию</strong> «Специалист офиса продаж».<br />
Своим сотрудникам Розничная сеть <em>обеспечивает</em>.</p>
<ul>
<li>Обучение за счет компании,
<li>Возможность переезда в другой город,
<li>Официальный доход.
<li>Премия, которая зависит только от твоей работы.
<li>Участие в корпоративных мероприятиях.
<li>Гибкий график работы.
<li>Полный социальный пакет (ДМС, оплата больничного, ежегодного отпуска, дополнительное страхование от несчастных случаев).
</ul>
]]></Description>
        <Salary>33000</Salary>
        <Images>
            <Image url=\"http://52.img.com/300x150/25719652_811142943.jpg\" />
        </Images>        
    </Ad>
    <Ad>
        <Id>vacancy2016-07-25-2</Id>
        <Region>Москва</Region>
        <Street>ул. Ленина, д. 9</Street>
        <Category>Вакансии</Category>           
        <Industry>Юриспруденция</Industry>
        <Title>Старший помошник юриста</Title>
        <JobType>Неполный день</JobType>
        <Experience>Не имеет значения</Experience>
        <Description>Требования:
- желание работать
- умение работать в команде
- нацеленность на результат
- высшее юридическое образование
Условия:
- стабильная работа в крупной компании
- полный социальный пакет
- оплачиваемый отпуск и больничный
- Стремительный карьерный рост
- Стабильность - официальное трудоустройство с первого дня, «белая» заработная плата, оплачиваемые отпуска и больничные листы
</Description>    
    </Ad>
</Ads>
";
?>