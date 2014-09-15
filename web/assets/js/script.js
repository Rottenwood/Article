$(document).ready(function () {

//////////////////////////////////////////////////
////                                          ////
////                Переменные                ////
////                                          ////
//////////////////////////////////////////////////

    var articleTable = $("table#articleTable tbody");


///////////////////////////////////////////////
////                                       ////
////                Функции                ////
////                                       ////
///////////////////////////////////////////////

    function getArticlesByAuthor(author) {
        $.post("api/getarticles", {
                author: author
            },
            function (data) {
                console.log(data);
                drawArticles(data);
            }, "json");
        return true;
    }

    function drawArticles(data) {
        articleTable.empty();
        $.each(data, function (key, value) {
            var articleId = key;
            var articleDate = value["date"];
            var articleTitle = value["title"];
            var articleAuthors = value["authors"];
            var articleRating = value["rating"];

            articleTable.append('<tr>' +
                '<td>' + articleDate + '</td>' +
                '<td><a class="openModalArticleLink" href="article/' + articleId + '/text" data-toggle="modal" data-target="#showarticleModal">' + articleTitle + '</a></td>' +
                '<td>' + articleAuthors + '</td>' +
                '<td class="textcenter">' + articleRating + '</td>' +
                '</tr>');

        });

        // Обновление сортировки таблицы
        $("#articleTable").trigger("update");
    }

///////////////////////////////////////////////
////                                       ////
////                События                ////
////                                       ////
///////////////////////////////////////////////

    // Выбор автора
    $(document).on("click", ".author-select a", function (event) {
        event.preventDefault();
        var author = $(this).data('id');

        getArticlesByAuthor(author);
    });

    // Очистка модальных окон после закрытия
    $('body').on('hidden.bs.modal', '.modal', function () {
        $(this).removeData('bs.modal');
    });

///////////////////////////////////////////////
////                                       ////
////         Запускаемые процедуры         ////
////                                       ////
///////////////////////////////////////////////

    // Динамическая сортировка таблицы
    $("#articleTable").tablesorter();

});
