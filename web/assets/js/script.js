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

    // Запрос статей по автору (0 - все статьи)
    function getArticlesByAuthor(author) {
        $.post("api/getarticles", {
                author: author
            },
            function (data) {
                drawArticles(data);
            }, "json");
        return true;
    }

    // Отрисовка таблицы статей
    function drawArticles(data) {
        articleTable.empty();
        // Если у автора нет статей
        if (data.length === 0) {
            articleTable.append('<tr>' +
                '<td></td>' +
                '<td class="search-result textcenter">Нет статей выбранного автора.</td>' +
                '<td></td>' +
                '<td></td>' +
                '</tr>');
        }
        $.each(data, function (key, value) {
            articleTable.append('<tr>' +
                '<td>' + value["date"] + '</td>' +
                '<td><a class="openModalArticleLink" href="article/' + key + '/text" data-toggle="modal" data-target="#showarticleModal">' + value["title"] +
                '<a href="article/' + key + '"><i class="fa fa-external-link article-external-link" title="Прямая ссылка на статью \"' + value["title"] + '\""></i></a>' +
                '</a></td>' +
                '<td>' + value["authors"] + '</td>' +
                '<td class="textcenter">' + value["rating"] + '</td>' +
                '<td class="textcenter"><button id="table-edit-button" class="btn btn-primary btn-xs" data-id="' + key + '" data-name="' + value["title"] + '" data-title="Edit" data-toggle="modal" data-target="#edit-article" data-placement="top" rel="tooltip"><span class="glyphicon glyphicon-pencil"></span></button></td>' +
                '<td class="textcenter"><button id="table-delete-button" class="btn btn-danger btn-xs" data-id="' + key + '" data-name="' + value["title"] + '" data-title="Delete" data-toggle="modal" data-target="#delete-article" data-placement="top" rel="tooltip"><span class="glyphicon glyphicon-trash"></span></button></td>' +
                '</tr>');
        });

        // Обновление сортировки таблицы
        $("#articleTable").trigger("update");
    }

    // Запрос создания статьи
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

    // Запрос редактирования статьи
    function editArticle(article, title, text, author) {
        $.post("api/edit", {
                article: article,
                title: title,
                text: text,
                author: author
            },
            function (data) {
                getArticlesByAuthor(0);
            }, "json");
        return true;
    }

    // Запрос удаления статьи
    function deleteArticle(articleId) {
        $.post("api/deletearticle", { articleId: articleId },
            function (data) {
                // действия после удаления статьи
            }, "json");
        return true;
    }

    // Запрос параметров статьи (название, текст, авторы)
    function getArticleParameters(articleId) {
        $.post("api/getonearticle", { articleId: articleId },
            function (data) {
                $('#article-title-edit').val(data["title"]);
                $('#article-text-edit').val(data["text"]);
                $('#article-author-edit-div').empty();
                $.each(data["authors"], function (key, value) {
                    var $this = $('div.form-group-options div.input-group-option');
                    var sInputGroupHtml = $this.html();
                    var sInputGroupClasses = $this.attr('class');
                    $('#article-author-edit-div').append('<div class="' + sInputGroupClasses + ' author-to-edit-' + key + '" data-author="' + value + '">' + sInputGroupHtml + '</div>');
                    $('#article-author-edit-div .author-to-edit-' + key + ' input').val(value);
                });

            }, "json");
        return true;
    }

    // Поисковый запрос
    function searchArticle(keyword) {
        $.post("api/search", { keyword: keyword },
            function (data) {
                $('input#search-field-text').val('');
                drawArticles(data);
                $('span.header-description').html('Поиск');
                $('td.search-result').html('Ничего не найдено по запросу "' + keyword + '"');
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
        var authorName = 'Выбрать автора';
        var authorNameTitle = 'Все статьи';
        if ($(this).data('name')) {
            authorName = $(this).data('name');
            authorNameTitle = authorName;
        }
        getArticlesByAuthor(author);
        $('#dropdownMenuAuthors span.author-name-select').html(authorName);
        $('span.header-description').html(authorNameTitle);
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

    // Удаление автора при редактировании статьи
    $(document).on('click', 'div.form-group-options .input-group-addon-remove', function () {
        // Не удаляем если открыто только одно поле для ввода автора
        if ($('#article-author-edit-div div').size() > 1) {
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

    // Отмена удаления в модальном окне
    $(document).on("click", "#cancel-modal-button", function () {
        $('#delete-article').modal('hide');
    });

    // Удаление статьи
    $(document).on("click", "#table-delete-button", function () {
        articleModalId = $(this).data('id');
    });

    $(document).on("click", "#delete-article-button", function () {
        deleteArticle(articleModalId);
        $('#delete-article').modal('hide');
        $('#openModalArticleLink' + articleModalId).closest('tr').remove();
    });

    // Редактирование статьи
    $(document).on("click", "#table-edit-button", function () {
        articleModalId = $(this).data('id');
        getArticleParameters(articleModalId);
    });

    $(document).on("click", "#edit-article-button", function () {
        var articleTitle = $('#article-title-edit').val();
        var articleText = $('#article-text-edit').val();

        var articleAuthor = [];

        $("#article-author-edit-div input").each(function () {
            var articleAuthorValue = $(this).val();

            if (articleAuthorValue) {
                articleAuthor.push(articleAuthorValue);
            }
        });

        editArticle(articleModalId, articleTitle, articleText, articleAuthor);

        $('#edit-article').modal('hide');
    });

    // Поиск
    $(document).on("click", "#add-article-header-button", function () {
        var keyword = $('input#search-field-text').val();
        searchArticle(keyword);
    });

///////////////////////////////////////////////
////                                       ////
////         Запускаемые процедуры         ////
////                                       ////
///////////////////////////////////////////////

    // Динамическая сортировка таблицы
    $("#articleTable").tablesorter({ headers: { 4: { sorter: false}, 5: {sorter: false} }});

});
