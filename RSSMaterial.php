
<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">

    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js"></script>
    <script>
        $(document).ready(function(){
            $(".button-collapse").sideNav();
            $.ajax({
                'url':'RSSProxy.php',
                'type': 'GET',
                'dataType': 'xml',
                'success': function(xmlResponse){
                    let items = $(xmlResponse).find("item");
                    $(items).each(function(){                        
                        let element =   {
                                            title: $(this).find("title").text(),
                                            desc: $(this).find("description").text(),
                                            img: $(this).find("StoryImage").text(),
                                            link: $(this).find("link").text(),
                                            updated_Date: $(this).find("updatedAt").text()
                                        };
                        generateCard(element);
                    });
                    
                }
            })
        });

        function generateCard(element){
            var cardRoot = document.createElement("div");
            cardRoot.classList = "col s12 m6";
            cardRoot.innerHTML =    '<div class="card horizontal">\
                                        <div class="card-image">\
                                            <img src="'+element.img+'">\
                                        </div>\
                                        <div class="card-stacked">\
                                            <div class="card-content">\
                                                <h2>'+element.title+'</h2>\
                                                <p>'+element.desc+'</p>\
                                            </div>\
                                        <div class="card-action">\
                                            <a href="'+element.link+'">Click here to view full story</a>\
                                        </div>\
                                    </div>';
            document.getElementById("containerRSS").appendChild(cardRoot);
            
        }
    </script>
</head>
<body>
<nav>
    <div class="nav-wrapper">
        <a href="#!" class="brand-logo">Logo</a>
        <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
        <ul class="right hide-on-med-and-down">
            <li><a href="sass.html">Sass</a></li>
            <li><a href="badges.html">Components</a></li>
            <li><a href="collapsible.html">Javascript</a></li>
            <li><a href="mobile.html">Mobile</a></li>
        </ul>
        <ul class="side-nav" id="mobile-demo">
            <li><a href="sass.html">Sass</a></li>
            <li><a href="badges.html">Components</a></li>
            <li><a href="collapsible.html">Javascript</a></li>
            <li><a href="mobile.html">Mobile</a></li>
        </ul>
        </div>
    </nav>
    <div class="container">
        <div id="containerRSS" class="row">
            
        </div>
        
    </div>
    </div>
</body>
</html>