<?

$links = array(
	"http://motochki.pro/catalog/anny-blatt/",
	"http://motochki.pro/catalog/color-city/",
	"http://motochki.pro/catalog/laines-du-nord/",
	"http://motochki.pro/catalog/lana-gatto/",
	"http://motochki.pro/catalog/seam/",
	"http://motochki.pro/catalog/businy-svarovski/",
	"http://motochki.pro/catalog/vita/",
	"http://motochki.pro/catalog/sock-yarn/",
	"http://motochki.pro/catalog/bbb/",
	"http://motochki.pro/catalog/drops/",
	"http://motochki.pro/catalog/illaria/",
	"http://motochki.pro/catalog/lana-grossa/",
	"http://motochki.pro/catalog/malabrigo/",
	"http://motochki.pro/catalog/mondial/",
	"http://motochki.pro/catalog/rowan/",
	"http://motochki.pro/catalog/pryazha-schachenmayr/",
	"http://motochki.pro/catalog/pryazha-dundaga-latviya/",
	"http://motochki.pro/catalog/yarn-italy/",
	"http://motochki.pro/catalog/yarn-peru/",
	"http://motochki.pro/catalog/pryazha-pod-pokrasku/",
	"http://motochki.pro/catalog/yarn-turkey/",
	"http://motochki.pro/catalog/alize/",
	"http://motochki.pro/catalog/jeans/",
	"http://motochki.pro/catalog/austermann/",
);

foreach ($links as $key => $link) {
	$result = @file_get_contents($link);

	if( !empty($result) ){
		file_put_contents("loadAll.txt", $link."\n", FILE_APPEND);
	}
}