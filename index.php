<?php  
    include $_SERVER["DOCUMENT_ROOT"]."/workout/settings.php";
    $session_on = false;
    $message = "";
    if (isset($_SESSION["email"])){
        $session_on = true;
    }
    if(isset($_GET["order"]) && $session_on){ 
        $name = $_GET["name"];
        $item = $_GET["item"];
        if ($item == 1){
            $item_name = "Детский массаж";
        }
        else if($item == 2){
            $item_name = "Общая физическая подготовка";
        }
        else if($item == 3){
            $item_name = "Актёрское мастерство";
        }
        $phone = $_GET["phone"];
        $time = $_GET["date"]." ".$_GET["select"];

        $stt = $db->prepare("SELECT COUNT(*) FROM orders WHERE time = ? AND item = ?");
        $stt->bindParam(1, $time);
        $stt->bindParam(2, $item_name);
        $stt->execute();
        $busy = $stt->fetch();
        if($busy[0] > 0){
            $message = "Такая запись уже существует";
        }
        else{
            
            $stt = $db->prepare("INSERT INTO orders (user_id, name, item, phone, time) VALUES (?,?,?,?,?)");
            $stt->bindParam(1, $_SESSION["id"]);
            $stt->bindParam(2, $name);
            $stt->bindParam(3, $item_name);
            $stt->bindParam(4, $phone);
            $stt->bindParam(5, $time);
            $stt->execute();
        }

    }
    elseif(isset($_GET["delete"])){
        $delete = $_GET["delete"];
        $stt = $db->prepare("DELETE FROM orders WHERE id = ?");
        $stt->bindParam(1, $delete);
        $stt->execute();
    }
    elseif(isset($_GET["order"]) && !$session_on){
        $message = "Войдите в систему или зарегистируйтесь";
    }
    $stt = $db->prepare("SELECT * FROM orders WHERE time > NOW()");
    $stt->execute();
    $data = $stt->fetchAll(PDO::FETCH_NUM);
    $records = false;
    if($session_on){
        $records = false;
        for($i = 0; $i < count($data); $i++){
            if($_SESSION["id"] == $data[$i][1] || $_SESSION["admin"] == 1){
                $records = true;
            }
        }
    }
    if(isset($_POST["feedback"])){
        //$feedback = filter_input(INPUT_POST, "feedback-text", FILTER_SANITIZE_SPECIAL_CHARS);
        $feedback = $_POST["feedback-text"];
        $mess = "
        <html>
        <head>
        <title>Сообщение с сайта</title>
        </head>
        <body>
        <h3>На сайте было оставлено сообщение</h3>
        <p>
        ".$feedback."
        </p>
        </body>
        </html>
        ";
        $headers = "From: Admin <noreply@w92614ji.beget.tech>\r\n". 
        "MIME-Version:1.0\r\n". 
        "Content-Type:text/html;charset=utf-8\r\n";
        if(mail("sadeaaa@yandex.ru", "Сообщение с сайта", $mess, $headers)){
            $message = "Ваше сообщение успешно отправлено!";
        }
        else{
            $message = "Не удалось отправить сообщение!";
        }

    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <link rel="stylesheet" href="/css/style.css?m=1">
    <link href="/favicons/fox_head2/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon">
    <link href="https://fonts.googleapis.com/css2?family=Bad+Script&family=Gaegu:wght@300;400;700&family=Marck+Script&display=swap" rel="stylesheet">
</head>

<body>
 
    <header id="header">
        <div class="container">
            <div class="header-content">
                <div>
                    <img src="/img/logo8.png" alt="">
                </div>
                <h1>Детский центр <br> Лисёнок Ники</h1>
                <p> 
                <?php if($session_on):  ?>
                    <a href="/logout/">Выйти</a>
                <?php else: ?>
                    <a href="/login/">Войти</a>
                    <br>
                    <a href="/register/">Зарегистрироваться</a>
                <?php endif; ?>
                </p>
            </div>
        </div>
    </header>
    <main id="main">
        <div class="container pro">
          
            <div class="main-content">
                <canvas width="400" height="281" id="canvas1"></canvas>
                <div>
                    <p><a href="/orders/">Детский массаж</a></p>
                    <p><a href="/actor/">Актёрское мастерство</a></p>
                    <p><a href="/lfk/">Общая физическая подготовка</a></p>
                </div>
                <canvas width="400" height="281" id="canvas2"></canvas>
            </div>
        </div>
        
    </main>
    |<section class="about" id="about">
        <div class="container">
            <div class="about-content">
                <h1>О нас</h1>
                <hr>
                <p>Добро пожаловать на сайт детского центра "Лисенок Ники"! Наш центр предлагает разнообразные развивающие занятия и мероприятия для детей от 7 до 18 лет. Мы стремимся создать уютную и безопасную среду, где каждый ребенок может раскрыть свой потенциал и развить свои таланты. Присоединяйтесь к нашему центру "Лисенок Ники" и дайте вашему ребенку возможность весело и интересно провести время, расширить свои знания и навыки, и построить новые дружеские отношения!</p>
                <div class="flex-wrapper">
                    <figure class="left">
                        <img src="/img/olly8.jpg" alt="Оля">
                        <figcaption>Ольга (медицинский массаж в педиатрии, преподаватель по актерскому мастерству 11-18 лет)</figcaption>
                    </figure>
                    
                    <figure class="right">
                    <img src="/img/nansy8.jpg" alt="Настя">
                        <figcaption>Анастасия (инструктор ОФП, преподаватель по актерскому мастерству 7-10 лет)</figcaption>
                    </figure>
                </div>
            </div>
        </div>
    </section>
    <section class="child-massage" id="child-massage">
        <div class="container">
            <div class="child-massage-content">
                <h1>Запишитесь к нам</h1>
                <hr>
                <h2>Дни приёма: <span>Вторник, Четверг</span></h2>
                <h3>Время приёма: <span>17:00-19:00</span></h3>
                <hr>
                <div class="seconddary-content">
                    <div class="left">
                        <form action="">
                            <h2>Запись</h2>
                            <input type="hidden" name="order" value="1">
                            <input type="text" placeholder="Ваше имя" name="name">
                            <select name="item" id="item">
                                <option value="1">Детский массаж</option>
                                <option value="2">Общая физическая подготовка</option>
                                <option value="3">Актёрское мастерство</option>
                            </select>
                            <input type="text" placeholder="Номер телефона" name="phone">
                            <label for="date">Выберите дату</label>
                            <input type="date" id="date" name="date">
                            <label for="select">Выберите время</label>
                            
                            <select name="select" id="select">
                                <option value="18:00:00">15:00</option>
                                <option value="18:00:00">16:00</option>
                                <option value="17:00:00">17:00</option>
                                <option value="18:00:00">18:00</option>
                            </select>
                            <button>Отправить</button>
                        </form>
                    </div>
                    <div class="right">
                        <h2>Записи</h2>
                        <ol class="list">
                            <?php for($i = 0; $i < count($data); $i++): ?>
                                
                                    <?php if($session_on && ( $_SESSION["admin"] == 1 || $_SESSION["id"] == $data[$i][1])):  ?>
                                <li>
                                    <?php echo $data[$i][2]." ".$data[$i][3]." ".$data[$i][4]."<br>".date("d.m.Y H:i", strtotime($data[$i][5])) ?>
                                    <a href="?delete=<?php echo $data[$i][0]?>"> &times; </a>
                                </li>
                                    <?php endif; ?>
                                
                            <?php endfor; ?>
                        </ol>
                        <?php if(!$records): ?> 
                            <div class="norecords">Вы пока не записаны</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="prices" id="prices">
        <div class="container">
            <h1>Наши цены</h1>
            <hr>
            <div class="prices-content">
                <ul class="massage">
                    <li class="price-name">Детский массаж</li>
                    <li class="price"><span class="massage-name">Массаж спины (60 минут)</span><span class="price-num"> 600 руб.</span></li>
                    <li class="price"></li>
                </ul>      
                <ul class="massage">
                    <li class="price-name">Актёрское мастерство</li>
                    <li class="price"><span class="massage-name">Мастер-класс(60 минут)</span><span class="price-num"> 500 руб.</span></li>
                    <li class="price"></li>
                </ul>  
                <ul class="massage">
                    <li class="price-name">Общая физическая подготовка</li>
                    <li class="price"><span class="massage-name">Массаж спины (60 минут)</span><span class="price-num"> 600 руб.</span></li>
                    <li class="price"></li>
                </ul>               
            </div>
        </div>
    </section>

    
    <section class="contact" id="contact">
        <div class="container"> 
            <div class="contact-wrapper">
                <div class="contact-content">
                    <h1>Наши контакты</h1>
                    <hr>
                    <p class="contact-info address">Наш адрес: <span></span></p>
                    <p class="contact-info phone">Наш телефон: <a href="tel:+79261464205">+7 926 146 42 05</a></p>
                    <p class="contact-info telegram">
                        <a href="https://t.me/Olenyonok_bemby"><img src="/img/telegram.png" alt="Александрова Ольга" width="40"></a>
                    </p>
                    <form action="" method="POST">
                        <input type="hidden" name="feedback" value="1">
                        <textarea name="feedback-text" id="feedback-text" placeholder="Напишите нам"></textarea>
                        <button type="submit" id="feedback-send">Отправить</button>
                    </form>
                </div>
                <div class="map-content">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2245.817148184452!2d37.23168677602267!3d55.74430597308104!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x46b5453ce9ab03e7%3A0xa21af811fcf0262f!2z0KjQutC-0LvQsCDQs9C10YDQvtC10LIgR3ltbmFzaXVt!5e0!3m2!1sru!2sru!4v1714317928605!5m2!1sru!2sru" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            
           
        </div>  
    </section>
    <footer>
        <p>
            &copy; Ольга Александрова. Все права защищены.
        </p>
    </footer>
    <div class="images">
            <img src="/img/1.png" id="1">
            <img src="/img/2.png" id="2">
            <img src="/img/3.png" id="3">
            <img src="/img/4.png" id="4">
            <img src="/img/5.png" id="5">
            <img src="/img/6.png" id="6">
            <img src="/img/7.png" id="7">
            <img src="/img/8.png" id="8">
            <img src="/img/9.png" id="9">
            <img src="/img/10.png" id="10">
            <img src="/img/11.png" id="11">
            <img src="/img/12.png" id="12">
            <img src="/img/13.png" id="13">
            <img src="/img/14.png" id="14">
            <img src="/img/15.png" id="15">
            <img src="/img/16.png" id="16">
            <img src="/img/17.png" id="17">
            <img src="/img/18.png" id="18">
            <img src="/img/19.png" id="19">
            <img src="/img/20.png" id="20">
            <img src="/img/21.png" id="21">
            <img src="/img/22.png" id="22">
            <img src="/img/23.png" id="23">
            <img src="/img/24.png" id="24">
            <img src="/img/25.png" id="25">
            <img src="/img/26.png" id="26">
            <img src="/img/27.png" id="27">
            <img src="/img/28.png" id="28">
            <img src="/img/29.png" id="29">
            <img src="/img/30.png" id="30">
            <img src="/img/31.png" id="31">
            <img src="/img/32.png" id="32">
            <img src="/img/33.png" id="33">
            <img src="/img/34.png" id="34">
            <img src="/img/35.png" id="35">
            <img src="/img/36.png" id="36">
            <img src="/img/37.png" id="37">
            <img src="/img/38.png" id="38">
            <img src="/img/39.png" id="39">
            <img src="/img/40.png" id="40">
            <img src="/img/41.png" id="41">
            <img src="/img/42.png" id="42">
            <img src="/img/43.png" id="43">
            <img src="/img/44.png" id="44">
            <img src="/img/babochka/00.png" id="b00">
            <img src="/img/babochka/01.png" id="b01">
            <img src="/img/babochka/02.png" id="b02">
            <img src="/img/babochka/03.png" id="b03">
            <img src="/img/babochka/04.png" id="b04">
            <img src="/img/babochka/05.png" id="b05">
            <img src="/img/babochka/06.png" id="b06">
            <img src="/img/babochka/07.png" id="b07">
            <img src="/img/babochka/08.png" id="b08">
            <img src="/img/babochka/09.png" id="b09">
            <img src="/img/babochka/10.png" id="b10">
        </div>
        <canvas id="babochka" width="60" height="60"></canvas>
        <img src="/img/rapper-flower-left.png" alt="" id="rfl1">
        <img src="/img/rapper-flower-right.png" alt="" id="rfr2">
        <img src="/img/rapper-flower-left.png" alt="" id="rfl3">
        <img src="/img/rapper-flower-right.png" alt="" id="rfr4">
        <img src="/img/rapper-flower-left.png" alt="" id="rfl5">
    <script src="/js/script.js"></script>
    <?php if($message != ""): ?>
        <script>  
            alert("<?php echo $message ?>");
        </script>
    <?php endif; ?>
</body>

</html>
