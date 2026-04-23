<?php
// --------- إعدادات الصفحة والروابط ---------
$page = $_GET['page'] ?? 'home';
$genre_link = $_GET['genre'] ?? '';
$series_link = $_GET['series'] ?? '';
$episode_link = $_GET['episode'] ?? '';
$baseURL = "https://f.alooytv8.xyz";
$fallbackImg = "https://up6.cc/2026/02/177059786970951.png";

// --------- معالجة تشغيل الفيديو بمشغل ExoPlayer الخاص بـ AppCreator24 ---------
if($page == 'episode' && $episode_link) {
    $html = file_get_contents($episode_link);
    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    preg_match('/source src="([^"]+\.mp4)"/', $html, $matches);
    $videoURL = $matches[1] ?? '';
    if($videoURL) {
        header("Location: $videoURL");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Beta TV - أبو حسين</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;800&family=Tajawal:wght@700;800&display=swap" rel="stylesheet" />
    
    <style>
        * { -webkit-tap-highlight-color: transparent; box-sizing: border-box; }
        body {
            padding: 0; margin: 0; 
            background-image: url(https://j.top4top.io/p_3578vhhqa0.jpg);
            background-repeat: no-repeat; background-size: cover; background-attachment: fixed;
            font-family: "Cairo", sans-serif; background-color: #000; color: #fff;
        }

        header {
            background: #000000 !important;
            position: fixed; top: 0; height: 60px; width: 100%; z-index: 1000;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.8);
        }
        header h3 { margin: 0; font-weight: 800; font-family: "Tajawal", sans-serif; font-size: 19px; color: #fff; }
        
        .nav-icon { position: absolute; font-size: 22px; color: #fff; cursor: pointer; top: 18px; text-decoration: none; }
        .right-icon { right: 20px; } 
        .left-icon { left: 20px; }

        .movies-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); grid-gap: 12px;
            padding: 80px 15px 100px 15px;
        }

        .movie-item {
            position: relative; background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-radius: 15px; border: 1px solid rgba(255, 255, 255, 0.15);
            overflow: hidden; transition: 0.2s ease; text-decoration: none; display: block;
            box-shadow: 0 8px 15px rgba(0,0,0,0.4);
        }
        .movie-poster { width: 100%; aspect-ratio: 2/3; object-fit: cover; display: block; }
        .movie-info { padding: 8px 4px; background: rgba(0, 0, 0, 0.5); }
        .movie-title {
            font-family: "Cairo", sans-serif; font-weight: 700; font-size: 11px;
            color: #ffffff; margin: 0; text-align: center;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        
        .genre-item { 
            background: rgba(231, 76, 60, 0.2); 
            height: 70px; display: flex; align-items: center; justify-content: center; 
            border: 1px solid rgba(231, 76, 60, 0.5); 
        }

        .episode-item { background: rgba(255,255,255,0.1); padding: 15px; border-radius: 12px; text-align: center; border: 1px solid rgba(255,255,255,0.15); }

        .sidenav { height: 100%; width: 0; position: fixed; z-index: 1001; top: 0; right: 0; background: #000; transition: 0.4s; padding-top: 60px; overflow-x: hidden;}
        .sidenav a { padding: 15px; text-decoration: none; font-size: 16px; color: #fff; display: block; text-align: center; border-bottom: 1px solid #222; cursor: pointer;}
        
        #searchOverlay { height: 0; width: 100%; position: fixed; z-index: 2000; top: 0; left: 0; background: rgba(0,0,0,0.98); overflow-y: auto; transition: 0.4s; }
        .search-header { padding: 20px; text-align: center; background: #000; position: sticky; top: 0; z-index: 2001; }
        .search-input { width: 90%; padding: 12px 20px; border-radius: 25px; border: 2px solid #333; background: #111; color: white; font-family: 'Cairo'; font-size: 16px; outline: none; }
    </style>
</head>
<body>

<header>
    <?php if($page != 'home'): ?>
        <a href="javascript:history.back()" class="nav-icon left-icon"><i class="fa fa-arrow-left"></i></a>
    <?php endif; ?>
    <h3>
        <?php 
            if($page=='home') echo 'الأقسام الرئيسية';
            elseif($page=='genre') echo 'قائمة المسلسلات';
            elseif($page=='series') echo 'حلقات المسلسل';
        ?>
    </h3>
    <span onclick="openNav()" class="nav-icon right-icon"><i class="fa fa-bars"></i></span>
</header>

<div id="mySidenav" class="sidenav">
    <div align="center"><img src="https://a.top4top.io/p_35783d0kv0.png" style="width: 140px; margin-bottom: 20px;" /></div>
    <a onclick="openSearch()"><i class="fa fa-search"></i> بحث في القائمة </a>
    <a href="?page=home"><i class="fa fa-home"></i> الرئيسية </a>
    <a onclick="closeNav()"> × إغلاق القائمة </a>
</div>

<div id="searchOverlay">
    <div class="search-header">
        <span style="color:#ff3b3b; font-weight:bold; cursor:pointer;" onclick="closeSearch()">إغلاق البحث ×</span><br><br>
        <input type="text" id="searchInput" class="search-input" placeholder="ابحث هنا..." oninput="filterGrid()">
    </div>
    <div class="movies-grid" id="searchResults"></div>
</div>

<main class="movies-grid" id="mainGrid">

<?php
/* =================== HOME (جلب جميع الأقسام) =================== */
if($page=='home'):
    $html = file_get_contents($baseURL);
    $html = mb_convert_encoding($html,'HTML-ENTITIES','UTF-8');
    libxml_use_internal_errors(true);
    $doc = new DOMDocument(); $doc->loadHTML($html); libxml_clear_errors();
    $xpath = new DOMXPath($doc);
    $nodes = $xpath->query('//a[contains(@href,"/genre/")]');
    $genres=[]; $seen=[];
    foreach($nodes as $node){
        $link=trim($node->getAttribute('href'));
        $name=trim($node->nodeValue);
        if($link && $name && !in_array($link,$seen)){
            if(strpos($link,'http')!==0) $link=$baseURL.$link;
            $genres[]=['name'=>$name,'link'=>$link];
            $seen[]=$link;
        }
    }
    foreach($genres as $g): ?>
        <a class="movie-item genre-item" href="?page=genre&genre=<?=urlencode($g['link'])?>">
            <div class="movie-title"><?=$g['name']?></div>
        </a>
    <?php endforeach;

/* =================== GENRE (جلب جميع المسلسلات - نظام الصفحات) =================== */
elseif($page=='genre' && $genre_link):
    $currentURL = $genre_link;
    $allSeries = [];
    
    // حلقة لجلب كافة الصفحات داخل القسم
    do {
        $html = @file_get_contents($currentURL);
        if(!$html) break;
        $html = mb_convert_encoding($html,'HTML-ENTITIES','UTF-8');
        libxml_use_internal_errors(true);
        $doc = new DOMDocument(); $doc->loadHTML($html); libxml_clear_errors();
        $xpath = new DOMXPath($doc);
        
        $seriesNodes = $xpath->query('//div[contains(@class,"col-md-2") and contains(@class,"col-sm-3")]');
        foreach($seriesNodes as $node){
            $titleNode = $xpath->query('.//div[contains(@class,"movie-title")]//h3/a', $node);
            $name = $titleNode->length ? $titleNode->item(0)->nodeValue : '';
            $link = $titleNode->length ? $titleNode->item(0)->getAttribute('href') : '';
            if($link && strpos($link,'http') !== 0) $link = $baseURL.$link;
            
            $imgNode = $xpath->query('.//img', $node);
            $img = $fallbackImg;
            if($imgNode->length){
                $img = trim($imgNode->item(0)->getAttribute('data-src')) ?: trim($imgNode->item(0)->getAttribute('src'));
                if($img && strpos($img,'http') !== 0) $img = $baseURL.$img;
            }
            
            if($name && $link) {
                $allSeries[] = ['name'=>$name, 'link'=>$link, 'img'=>$img];
            }
        }
        
        // البحث عن رابط الصفحة التالية
        $nextPageNode = $xpath->query('//a[@rel="next"]');
        if($nextPageNode->length > 0) {
            $nextPath = $nextPageNode->item(0)->getAttribute('href');
            $currentURL = (strpos($nextPath,'http') === 0) ? $nextPath : $baseURL.$nextPath;
        } else {
            $currentURL = null; // لا توجد صفحات أخرى
        }
    } while($currentURL);

    foreach($allSeries as $s): ?>
        <a class="movie-item" href="?page=series&series=<?=urlencode($s['link'])?>&genre=<?=urlencode($genre_link)?>" data-name="<?=strtolower($s['name'])?>">
            <img src="<?=$s['img']?>" class="movie-poster">
            <div class="movie-info">
                <p class="movie-title"><?=$s['name']?></p>
            </div>
        </a>
    <?php endforeach;

/* =================== SERIES (جلب جميع الحلقات) =================== */
elseif($page=='series' && $series_link):
    $html = file_get_contents($series_link);
    $html = mb_convert_encoding($html,'HTML-ENTITIES','UTF-8');
    libxml_use_internal_errors(true);
    $doc = new DOMDocument(); $doc->loadHTML($html); libxml_clear_errors();
    $xpath = new DOMXPath($doc);
    $seasons = $xpath->query('//div[contains(@class,"season")]');
    $epCount = 1;
    foreach($seasons as $season):
        $episodeNodes = $xpath->query('.//a[contains(@href,"/watch/")]', $season);
        foreach($episodeNodes as $ep):
            $epLink = $ep->getAttribute('href');
            if($epLink && strpos($epLink,'http') !== 0) $epLink = $baseURL.$epLink; ?>
            <a class="movie-item episode-item" href="?page=episode&episode=<?=urlencode($epLink)?>">
                <div class="movie-title">الحلقة <?=$epCount?></div>
            </a>
    <?php $epCount++; endforeach; endforeach;
endif; ?>

</main>

<script>
function openNav() { document.getElementById("mySidenav").style.width = "250px"; }
function closeNav() { document.getElementById("mySidenav").style.width = "0"; }
function openSearch() { 
    closeNav(); 
    document.getElementById("searchOverlay").style.height = "100%"; 
    document.getElementById("searchResults").innerHTML = document.getElementById("mainGrid").innerHTML;
}
function closeSearch() { document.getElementById("searchOverlay").style.height = "0"; }

function filterGrid() {
    let val = document.getElementById('searchInput').value.toLowerCase();
    let cards = document.getElementById("searchResults").getElementsByClassName('movie-item');
    for (let card of cards) {
        let name = card.getAttribute('data-name') || "";
        card.style.display = name.includes(val) ? "" : "none";
    }
}
</script>

</body>
</html>
