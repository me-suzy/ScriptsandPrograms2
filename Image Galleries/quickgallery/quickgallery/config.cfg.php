<?
    // Galeria ver 1.0
    // Autor : Marcin Chmielecki (marcin@chiliweb.com.pl)
    // Sponsor : Chili Web Applications http://www.chiliweb.com.pl
    
    
    /////////////////////////
    // ustawienia
    
    // ubranka dla strony, mozliwy jeden z ponizszych wariantow
    // some skins, choose one from below
    // gallery.css gallery2.css gallery3.css gallery4.css gallery5.css
    $a = rand(1,5);

    // $_style_ = 'gallery1.css';
    $_style_ = 'gallery'.$a.'.css';
    
    // nazwa strony
    // page title
    $_page_title_ = 'This is example of QuickGalery<br><small>Skin is random automatically</small>';
    
    // katalog ze zdjeciami
    // image directory
    $_images_dir_ = 'images/';
    
    // szerokosc miniaturki zdjecia
    // min width of image
    $_width_min_ = 100 ;
    // wysokosc miniaturki zdjecia
    // min height of image
    $_height_min_ = 100 ;

    //jakosc prezentowanego zdjecia, im lepsza tym wiekszy rozmiar
    // quality of image, better quality more size of image
    // $_quality_ = 0..100
    $_quality_ = 80;
    
    // odstep od zdjecia w poziomie
    $_vspace_ = 10 ;
    // odstep od zdjecia w pionie
    $_hspace_ = 10 ;
    // obramowanie zdjecia w pikselach
    // border of image
    $_border_ = 1;
    
    // sortowanie ASC rosnaco, DESC malejaco
    // sorting
    // SORT_ASC, SORT_DESC
    $_sort_ = SORT_ASC;
    
    
    // szerokosc calego zdjecia 
    // pelny wymiar zdjec, ustaw zmienna na 0
    // width of full image
    // if original width, set this variable to 0 
    $_width_max_ = 640 ;

    // czy pokazywac nazwy plikow graficznych pod ikonkami ze zdjeciami
    // 1 = TAK, 0 = NIE
    // show filename below image
    // 1 = YES, 0 = NO
    $_filename_ = 1;

    // ilosc kolumn w galerii
    // number of cols
    $_no_cols_ = 3;
    
    // ilosc zdjec na stronie (= ilosc kolumn x ilosc wierszy)
    // number of images on the site ( = number rows x number cols)
    $_no_pics_per_page_ = 9;
    

?>