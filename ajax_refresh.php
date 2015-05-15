<?php
$l="fr"; 
if (isset($_COOKIE['l']))
	$l=$_COOKIE['l'];
$mode=0;
if (isset($_COOKIE['mode']))
	$mode=intval($_COOKIE['mode']);
$trads = array(
"ar" => array(
	"1" => "ملصق [ar]",
	"170" => "المخترع",
	"31" => "هو",
	"276" => "الموقع",
	"195" => "مجموعة",
	"135" => "التيار الأدبي",
	"136" => "نوع أدبي",
	"179" => "مجموعة",
	"180" => "يصور",
	"186" => "مادة",
	"361" => "جزء من",
	"144" => "على أساس",
	"921" => "عنوان الموضوع",
	"941" => "مستوحاة من"
),
"bn" => array(
	"1" => "লেবেল [bn]",
	"170" => "স্রষ্টা",
	"31" => "নিদর্শন",
	"276" => "অবস্থান",
	"195" => "সংগ্রহ",
	"135" => "আন্দোলন",
	"136" => "ধরন",
	"179" => "সিরিজ",
	"180" => "রচিত",
	"186" => "উপাদান",
	"361" => "সদস্য",
	"144" => "এর উপর ভিত্তি করে",
	"921" => "বিষয় শিরোনাম",
	"941" => "দ্বারা অনুপ্রাণিত"
),
"br" => array(
	"1" => "Anv [br]",
	"170" => "Krouer",
	"31" => "Doare an elfenn",
	"276" => "Lec'hiadur a-vremañ",
	"195" => "Dastumadeg",
	"135" => "Luskad",
	"136" => "Doare",
	//"179" => "Series",
	"180" => "Skeudennet",
	"186" => "Danvezioù",
	"361" => "Ezel eus"
	/*"144" => "Based on",
	"921" => "Subject heading",
	"941" => "Inspired by"*/
),
"ca" => array(
	"1" => "Etiqueta [ca]",
	"170" => "Creador",
	"31" => "Instància de",
	"276" => "Lloc actual",
	"195" => "Collecció",
	"135" => "Moviment",
	"136" => "Gènere artístic",
	"179" => "Sèrie",
	"180" => "Subjecte representat",
	"186" => "Material",
	"361" => "Part de",
	"144" => "Basat enn",
	"921" => "Tema principal",
	"941" => "Inspirat per"
),
"cs" => array(
	"1" => "Štítek [cs]",
	"170" => "Tvůrce",
	"31" => "Instance (čeho)",
	"276" => "Umístění",
	"195" => "Sbírka",
	"135" => "Umělecký směr",
	"136" => "Zánr",
	"179" => "Série",
	"180" => "Motiv",
	"186" => "Materiál",
	"361" => "část",
	"144" => "Podle",
	"921" => "Hlavní námět díla",
	"941" => "Inspirován"
),
"de" => array(
	"1" => "Bezeichnung [de]",
	"170" => "Urheber",
	"31" => "Ist ein(e)",
	"276" => "Standort",
	"195" => "Sammlung",
	"135" => "Bewegung",
	"136" => "Genre",
	"179" => "Serie",
	"180" => "Motiv",
	"186" => "Material",
	"361" => "Ist Teil von",
	"144" => "Vorlage",
	"921" => "Schlagwort",
	"941" => "Inspiriert von"
),
"el" => array(
	"1" => "Ετικέτα [el]",
	"170" => "Δημιουργός",
	"31" => "Είναι",
	"276" => "Τρέχουσα τοποθεσία",
	"195" => "Συλλογή",
	"135" => "Λογοτεχνικό ρεύμα",
	"136" => "Είδος έργου",
	"179" => "Σειρές",
	"180" => "Απεικονίζει",
	"186" => "Υλικό",
	"361" => "Μέρος τουf",
	"144" => "Βασίζεται στο",
	"921" => "Θέμα ενός έργου δημιουργίας",
	"941" => "Εμπνευσμένο από"
),
"en" => array(
	"1" => "Title [en]",
	"170" => "Creator",
	"31" => "Type",
	"276" => "Location",
	"195" => "Collection",
	"135" => "Movement",
	"136" => "Genre",
	"179" => "Series",
	"180" => "Depicts",
	"186" => "Material used",
	"361" => "Part of",
	"144" => "Based on",
	"921" => "Subject heading",
	"941" => "Inspired by"
),
"eo" => array(
	"1" => "Etikedo [eo]",
	"170" => "Kreinto",
	"31" => "Estas",
	"276" => "Loko",
	"195" => "Kolekto",
	"135" => "Movado",
	"136" => "Genro",
	"179" => "Serio",
	"180" => "Figuri",
	"186" => "Materialo",
	"361" => "Parto de",
	"144" => "Surbaze de",
	"921" => "Ceftemo",
	"941" => "Inspirita"
),
"es" => array(
	"1" => "Etiqueta [es]",
	"170" => "Creador",
	"31" => "Es un/una",
	"276" => "Ubicación actual",
	"195" => "Colección",
	"135" => "Movimiento",
	"136" => "Género artístico",
	"179" => "Serie",
	"180" => "Representa",
	"186" => "Material",
	"361" => "Parte de",
	"144" => "Basado en",
	"921" => "Tema principal",
	"941" => "Inspirado por"
),
"fa" => array(
	"1" => "برچسب [fa]",
	"170" => "پدیدآورنده",
	"31" => "یک نمونه از",
	"276" => "مکان کنونی (حذف شد)",
	"195" => "گرداوری",
	"135" => "جنبش ادبی",
	"136" => "سبک",
	"179" => "دنباله",
	"180" => "توصیف‌ها",
	"186" => "مواد سازنده",
	"361" => "جزئی از",
	"144" => "بر پایهٔ",
	"921" => "موضوع اصلی",
	"941" => "با الهام از"
),
"fi" => array(
	"1" => "Nimi [fi]",
	"170" => "Luoja",
	"31" => "Esiintymä kohteesta",
	"276" => "Nykyinen sijainti",
	"195" => "Kokoelma",
	"135" => "Suuntaus",
	"136" => "Lajityyppi",
	"179" => "Sarja",
	"180" => "Esittää",
	"186" => "Materiaali",
	"361" => "Osa kohdetta",
	"144" => "Based on",
	"921" => "Subject heading",
	"941" => "Inspired by"
),
"fr" => array(
	"1" => "Titre [fr]",
	"170" => "Créateur",
	"31" => "Type",
	"276" => "Localisation",
	"195" => "Collection",
	"135" => "Mouvement",
	"136" => "Genre",
	"179" => "Série",
	"180" => "Dépeint",
	"186" => "Matériau",
	"361" => "Partie de",
	"144" => "Basé sur",
	"921" => "Sujet de l'œuvre",
	"941" => "Inspiré de"
),
"he" => array(
	"1" => "לְכַנוֹת [he]",
	"170" => "יוצר",
	"31" => "הוא",
	"276" => "מקום נוכחי",
	"195" => "אוסף/מוזיאון",
	"135" => "ארגון ספרותי",
	"136" => "סוגה",
	"179" => "סדרה",
	"180" => "מתאר",
	"186" => "עשוי מ",
	"361" => "חלק מתוך",
	"144" => "מבוסס על",
	"921" => "נושא היצירה",
	"941" => "השראה"
),
"hi" => array(
	"1" => "उपनाम [hi]",
	"170" => "रचियता",
	"31" => "उदहारण है",
	"276" => "वर्तमान स्थान",
	"195" => "समूह",
	"135" => "आंदोलन",
	"136" => "शैली",
	"179" => "सीरीज",
	"180" => "चित्रखींचना",
	"186" => "पदार्थ",
	"361" => "का भाग",
	"144" => "पर आधारित",
	"921" => "विषय - सूची",
	"941" => "से प्रेरित"
),
"id" => array(
	"1" => "Label [id]",
	"170" => "Pencipta",
	"31" => "Adalah",
	"276" => "Lokasi",
	"195" => "Koleksi",
	"135" => "Sebuah aliran",
	"136" => "Genre",
	"179" => "Bagian dari",
	"180" => "Menggambarkan",
	"186" => "Bahan yang digunakan",
	"361" => "Part of",
	"144" => "Berdasarkan",
	"921" => "Judul subjek",
	"941" => "Terinspirasi oleh"
),
"it" => array(
	"1" => "Etichetta [it]",
	"170" => "Creatore",
	"31" => "È un/una",
	"276" => "Posizione attuale",
	"195" => "Collezione",
	"135" => "Movimento artistico",
	"136" => "Genere artistico",
	"179" => "Serie",
	"180" => "Raffigura",
	"186" => "Materiale usato",
	"361" => "Parte di",
	"144" => "Basato su",
	"921" => "Argomento principale",
	"941" => "Ispirato da"
),
"ja" => array(
	"1" => "Label [ja]",
	"170" => "作者",
	"31" => "以下の実体",
	"276" => "現在の所在地",
	"195" => "所蔵元",
	"135" => "文学運動",
	"136" => "ジャンル",
	"179" => "シリーズ",
	"180" => "題材",
	"186" => "材料",
	"361" => "以下の一部分",
	"144" => "原作",
	"921" => "著作物の主要議題",
	"941" => "触発され"
),
"jv" => array(
	"1" => "Label [jv]",
	"170" => "Nitahake",
	"31" => "Tipe",
	"276" => "Panggon saiki",
	"195" => "Koleksi",
	"135" => "Gerakan",
	"136" => "Genre",
	"179" => "Series",
	"180" => "Nggambarake",
	"186" => "Materi",
	"361" => "Bagean",
	"144" => "Adhedhasar",
	"921" => "Subyek judhul",
	"941" => "Inspirasi dening"
),
"ko" => array(
	"1" => "레이블 [ko]",
	"170" => "작가",
	"31" => "종류",
	"276" => "현재 위치",
	"195" => "소장처",
	"135" => "예술적 운동",
	"136" => "장르",
	"179" => "시리즈",
	"180" => "묘사",
	"186" => "재료",
	"361" => "전체 부분",
	"144" => "원작",
	"921" => "주요 소재",
	"941" => "영감을 준 것"
),
"mu" => array(
	"1" => "Houba",
	"170" => "Houba",
	"31" => "Houba",
	"276" => "Houba",
	"195" => "Houba",
	"135" => "Houba",
	"136" => "Houba",
	"179" => "Houba",
	"180" => "Houba",
	"186" => "Houba",
	"361" => "Houba",
	"144" => "Houba",
	"921" => "Houba",
	"941" => "Houba"
),
"nl" => array(
	"1" => "Label [nl]",
	"170" => "Maker",
	"31" => "Is een",
	"276" => "Locatie",
	"195" => "Collectie",
	"135" => "Stroming",
	"136" => "Genre",
	"179" => "Serie",
	"180" => "Beeldt af",
	"186" => "Materialen",
	"361" => "Onderdeel van",
	"144" => "Gebaseerd op",
	"921" => "Hoofdonderwerp",
	"941" => "Geïnspireerd door"
),
"pa" => array(
	"1" => "ਲੇਬਲ [pa]",
	"170" => "ਸਿਰਜਨਹਾਰ",
	"31" => "ਕਿਸਮ",
	"276" => "ਪਤਾ",
	"195" => "ਅਰਜਨ ",
	"135" => "ਲਹਿਰ ",
	"136" => "ਧੁਨ",
	"179" => "ਸੀਰੀਜ਼",
	"180" => "ਜਾਦਾ ਹੈ",
	"186" => "ਸਮਗਰੀ",
	"361" => "ਦੇ ਭਾਗ",
	"144" => "'ਤੇ ਆਧਾਰਿਤ",
	"921" => "ਵਿਸ਼ਾ ਸਿਰਲੇਖ",
	"941" => "ਕੇ ਪ੍ਰੇਰਿਤ"
),
"pl" => array(
	"1" => "Etykieta [pl]",
	"170" => "Twórca",
	"31" => "Jest to",
	"276" => "Lokalizacja",
	"195" => "Kolekcja",
	"135" => "Kierunek w sztuce",
	"136" => "Gatunek utworu",
	"179" => "Cykl",
	"180" => "Przedstawia",
	"186" => "Materiał",
	"361" => "Część",
	"144" => "Na podstawie",
	"921" => "Temat nagłówek",
	"941" => "Zainspirowane przez",
),
"pt" => array(
	"1" => "Etiqueta [pt]",
	"170" => "Criador",
	"31" => "Instância de",
	"276" => "Localização",
	"195" => "Coleção",
	"135" => "Movimento",
	"136" => "Género artístico",
	"179" => "Série",
	"180" => "Retrata",
	"186" => "Material utilizado",
	"361" => "Parte de",
	"144" => "Adaptado de",
	"921" => "Tópico principal",
	"941" => "Inspirado por",
),
"ru" => array(
	"1" => "Метка [ru]",
	"170" => "Создатель объекта",
	"31" => "Является",
	"276" => "Текущее местонахождение",
	"195" => "Хранится В Коллекции",
	"135" => "Художественное направление",
	"136" => "Жанр",
	"179" => "Входить у цикл",
	"180" => "Изображённый Объект",
	"186" => "Материал",
	"361" => "Часть от",
	"144" => "Создано на основе",
	"921" => "Основная тема ",
	"941" => "Вдохновлено",
),
"sv" => array(
	"1" => "Etikett [sv]",
	"170" => "Skapare",
	"31" => "Är en/ett",
	"276" => "Plats",
	"195" => "Samling",
	"135" => "Rörelse",
	"136" => "Genre",
	"179" => "Serie",
	"180" => "Motiv",
	"186" => "Material",
	"361" => "Sel av",
	"144" => "Baserad på",
	"921" => "Ämnesord",
	"941" => "Inspirerad av"
),
"sw" => array(
	"1" => "Lebo [sw]",
	"170" => "Muumba",
	"31" => "Aina",
	"276" => "Eneo",
	"195" => "Ukusanyaji",
	"135" => "Mwelekeo mkuu",
	"136" => "Utanzu",
	"179" => "Mfululizo",
	"180" => "Wakilisha",
	"186" => "Vifaa",
	"361" => "Sehemu ya",
	"144" => "Msingi",
	"921" => "Somo kichwa",
	"941" => "Aliongoza kwa"
),
"te" => array(
	"1" => "లేబుల్ [te]",
	"170" => "సృష్టికర్త",
	"31" => "అంశ",
	"276" => "ప్రస్తుత ప్రాంతం",
	"195" => "సేకరణ",
	"135" => "ఉద్యమం",
	"136" => "రచనా శైలి",
	"179" => "పరంపర",
	"180" => "చిత్రణ",
	"186" => "వాడిన సామాగ్రి",
	"361" => "దీనిలో భాగం",
	"144" => "ఆధారితం",
	"921" => "విషయపు శీర్షిక",
	"941" => "స్ఫూర్తి పొందినది",
),
"th" => array(
	"1" => "ติดป้าย  [th]",
	"170" => "ผู้สร้าง",
	"31" => "เป็น",
	"276" => "ตำแหน่งที่อยู่",
	"195" => "การเก็บ",
	"135" => "ขบวนการ",
	"136" => "ประเภท",
	"179" => "ซีรีส์",
	"180" => "วาดให้เห็น",
	"186" => "วัสดุ",
	"361" => "ส่วนหนึ่งของ",
	"144" => "อยู่บนพื้นฐานของ",
	"921" => "หัวเรื่อง",
	"941" => "แรงบันดาลใจจาก"
),
"tr" => array(
	"1" => "Etiket [tr]",
	"170" => "Yaratıcı",
	"31" => "Bir",
	"276" => "Mekan",
	"195" => "Koleksiyon",
	"135" => "Akım",
	"136" => "Tarz",
	"179" => "Serisi",
	"180" => "Tasvir etmektedir",
	"186" => "Gereç",
	"361" => "Bir parçası",
	"144" => "Uyarlandığı eser",
	"921" => "Konu başlığı",
	"941" => "Esinlenilmiştir"
),
"uk" => array(
	"1" => "Мітка [uk]",
	"170" => "Творець",
	"31" => "Є одним із",
	"276" => "Поточне розміщення",
	"195" => "Колекції",
	"135" => "Літературний напрям",
	"136" => "Жанр",
	"179" => "Входить у цикл",
	"180" => "Зображує",
	"186" => "Матеріалу",
	"361" => "Частина від",
	"144" => "Ґрунтується на",
	"921" => "Предметна рубрика",
	"941" => "Натхненний"
),
"vi" => array(
	"1" => "Danh hiệu [vi]",
	"170" => "Người sáng tạo",
	"31" => "Là một",
	"276" => "Vị trí hiện tại",
	"195" => "Tập hợp",
	"135" => "Phong trào văn học",
	"136" => "Thể loại",
	"179" => "Loạt",
	"180" => "Mô tả",
	"186" => "Chất liệu",
	"361" => "Một phần của",
	"144" => "Phỏng theo",
	"921" => "Chủ đề",
	"941" => "Lấy cảm hứng từ"
),
"zh" => array(
	"1" => "标签 [zh]",
	"170" => "创作者",
	"31" => "性质",
	"276" => "现在位置",
	"195" => "收藏于",
	"135" => "文化运动",
	"136" => "艺术流派",
	"179" => "系列",
	"180" => "描绘内容",
	"186" => "所用材料",
	"361" => "属于",
	"144" => "改編自",
	"921" => "作品主题",
	"941" => "灵感来自"
)

);
function translate($lg,$term){
	global $trads;
    if ($trads[$lg][$term])
		return $trads[$lg][$term];
	elseif ($trads["en"][$term])
		return $trads["en"][$term];
	else
		return "";
}
include "config.php";
$link = mysqli_connect ($host,$user,$pass,$db) or die ('Erreur : '.mysqli_error());
mysqli_query($link,"SET NAMES 'utf8'");

function label_item($qwd,$lg){
	global $link;
    $sql="SELECT label from label_page WHERE qwd=$qwd AND lg='$lg' AND label!='' LIMIT 0,1";
	$rep_lab=mysqli_query($link,$sql);
	$num_rows= mysqli_num_rows($rep_lab);
	if ($num_rows==0){
		$sql="SELECT label from label_page WHERE qwd=$qwd AND lg='en' AND label!='' LIMIT 0,1";
		$rep_lab=mysqli_query($link,$sql);
		$num_rows = mysqli_num_rows($rep_lab);
		if ($num_rows==0){
			$sql="SELECT label from label_page WHERE qwd=$qwd AND label!='' LIMIT 0,1";
			$rep_lab=mysqli_query($link,$sql);
			$num_rows = mysqli_num_rows($rep_lab);
		}
	}
	if ($num_rows!=0){
		$data_lab = mysqli_fetch_assoc($rep_lab);
		$label=$data_lab['label'];
	}else
		$label="";
	
	/* Easter egg */if ($lg=="mu") return "Houba"; else	
	return $label;
} 
$keyword = $_GET['keyword'];
$img="img";
if ($mode==1)
	$img="";
$cpt=0;
$ls ="";
$sql = "SELECT prop,type, qwd, label, id_art_or_prop FROM label_page WHERE lg=\"".$l."\" AND label LIKE '%".$keyword ."%' AND nb".$img."!=0 GROUP BY prop, qwd ORDER BY nb".$img." DESC LIMIT 0, 5";
$rep=mysqli_query($link,$sql);
while ($rs = mysqli_fetch_assoc($rep)){
	$cpt++;
	$rs['label'] = preg_replace("/".$keyword."/i", "<b>\$0</b>",$rs['label']);
	if ($rs['type']==2)
		$rs['label']=$rs['label'].' <span class="als">('.label_item($rs['qwd'],$l).')</span>';
		
	$txt="";
	if ($rs['prop']==1){
		$sql="SELECT commons_img.P18 as img, commons_img.width, commons_img.height from artworks, commons_img  WHERE artworks.id=".$rs['id_art_or_prop']." AND artworks.P18=commons_img.id";
		$rep2=mysqli_query($link,$sql);
		$thumb="/crotos/img/nis.png";
		while ($data2 = mysqli_fetch_assoc($rep2)){
			$img=str_replace(" ","_",$data2['img']);
			$digest = md5($img);
			$folder = $digest[0] . '/' . $digest[0] . $digest[1] . '/' . urlencode($img);
			$w_thumb=floor(intval($data2['width'])/intval($data2['height'])*30);
			if ($w_thumb>70)
				$w_thumb=70;
			$thumb="http://upload.wikimedia.org/wikipedia/commons/thumb/" . $folder."/".$w_thumb."px-". urlencode($img);
			if (substr ($img,-3)=="svg")
				$thumb.=".png";	
		}
		$sql="SELECT p170.qwd as prop_qwd, dates from artw_prop,p170 WHERE artw_prop.prop=170 AND  artw_prop.id_artw=".$rs['id_art_or_prop']." AND  artw_prop.id_prop=p170.id";
		$rep2=mysqli_query($link,$sql);
		$crea="";
		while ($data2 = mysqli_fetch_assoc($rep2)){
			$tl=label_item(intval($data2['prop_qwd']),$l);
			if ($tl!=""){
				$crea.=", ".$tl;
				if ($data2['dates']!="")
					$crea.=" ".$data2['dates'];
			}
		}
		if ($crea!="")
			$crea='<span class="lbs">'.$crea.'</span>';
		$txt.='q='.$rs['qwd'].'¤<span class="ims"><span class="is"><img src="'.$thumb.'"/></span><span class="lbs">'.$rs['label'].'</span><span class="lbi">'.$crea.'</span></span>';
	}
	elseif ($rs['prop']==170){
		$sql="SELECT dates from p170 WHERE id=".$rs['id_art_or_prop'];
		$rep2=mysqli_query($link,$sql);
		while ($data2 = mysqli_fetch_assoc($rep2))
			if ($data2['dates']!="")
				$rs['label'].=" ".$data2['dates'];
		$txt.='p'.$rs['prop'].'='.$rs['qwd'].'¤<span class="lis">'.translate($l,$rs['prop']).'</span> '.$rs['label'];
	}
	else
		$txt.='p'.$rs['prop'].'='.$rs['qwd'].'¤<span class="lis">'.translate($l,$rs['prop']).'</span> '.$rs['label'];

	if ($cpt!=1)
		$ls.="|";
	$ls.=$txt;
}
echo $ls;
?>