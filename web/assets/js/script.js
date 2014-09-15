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
                drawArticles(data);
            }, "json");
        return true;
    }

    function drawArticles(data) {
        articleTable.empty();
        // Если у автора нет статей
        if (data.length === 0) {
            articleTable.append('<tr>' +
                '<td></td>' +
                '<td class="textcenter">Нет статей выбранного автора.</td>' +
                '<td></td>' +
                '<td></td>' +
                '</tr>');
        }
        $.each(data, function (key, value) {
            articleTable.append('<tr>' +
                '<td>' + value["date"] + '</td>' +
                '<td><a class="openModalArticleLink" href="article/' + key + '/text" data-toggle="modal" data-target="#showarticleModal">' + value["title"] + '</a></td>' +
                '<td>' + value["authors"] + '</td>' +
                '<td class="textcenter">' + value["rating"] + '</td>' +
                '</tr>');
        });

        // Обновление сортировки таблицы
        $("#articleTable").trigger("update");
    }

    function createArticle(title, text, author) {
        $.post("api/addarticle", {
                title: title,
                text: text,
                author: author
            },
            function (data) {
                getArticlesByAuthor(0);
            }, "json");
        return true;
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

    // Создание новой статьи
    $(document).on("click", "#add-article-button", function () {
        var articleTitle = $("#article-title-add").val();
        var articleContext = $("#article-text-add").val();
        var articleAuthor = [];

        $("#article-author-add-div input").each(function () {
            var articleAuthorValue = $(this).val();

            if (articleAuthorValue) {
                articleAuthor.push(articleAuthorValue);
            }
        });

        createArticle(articleTitle, articleContext, articleAuthor);

        $('#add-article').modal('hide');
    });

    // Добавление авторов в статью
    $(document).on('focus', 'div.form-group-options div.input-group-option:last-child input', function () {
        var sInputGroupHtml = $(this).parent().html();
        var sInputGroupClasses = $(this).parent().attr('class');
        $(this).parent().parent().append('<div class="' + sInputGroupClasses + '">' + sInputGroupHtml + '</div>');
    });

    // Удаление автора при добавлении статьи
    $(document).on('click', 'div.form-group-options .input-group-addon-remove', function () {
        // Не удаляем если открыто только одно поле для ввода автора
        if ($('#article-author-add-div div').size() > 1) {
            $(this).parent().remove();
        }
    });

    // Очистка модального окна создания статьи
    $(document).on("click", "#add-article-header-button", function (event) {
        $('#article-title-add').val('');
        $('#article-text-add').val('');
        $('#article-author-add-div div').each(function () {
            if ($('#article-author-add-div div').size() > 1) {
                $(this).remove();
            }
        });
    });

///////////////////////////////////////////////
////                                       ////
////         Запускаемые процедуры         ////
////                                       ////
///////////////////////////////////////////////

    // Динамическая сортировка таблицы
    $("#articleTable").tablesorter();

});
