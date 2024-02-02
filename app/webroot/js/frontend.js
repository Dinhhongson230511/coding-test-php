$(document).ready(function() {
    getArticles();

    /** Handle get data to display **/
        function getArticles() {
            getData('/articles.json', 'json', 'articles-list');
        }

        function getArticlesByUser() {
            getData('/article/list.json', 'json', 'user-articles-list');
        }
    /** Handle get data to display **/

    /** Handle display **/
        function displayArticles(articles) {
            // Clear existing content
            $('#articles-list').empty();

            // Iterate through the articles and append them to the list
            $.each(articles, function(index, article) {
                $('#articles-list').append(`
                    <li style="font-size:20px;">
                        <a href="">${article.title}</a>
                        <span class="like-article" data-id="${article.id}" style="cursor: pointer;">${article.like_count} ${article.likes.length > 0 ? '<i class="fa-solid fa-thumbs-up"></i>' : '<i class="fa-regular fa-thumbs-up"></i>'}<span>
                    </li>
                `);
            });
        }

        function displayArticlesByUser(articles) {
            // Clear existing content
            $('#user-articles-list').empty();

            // Iterate through the articles and append them to the list
            $.each(articles, function(index, article) {
                $('#user-articles-list').append(`
                    <tr>
                        <td>${article.id}</td>
                        <td>${article.user.email}</td>
                        <td>${article.title}</td>
                        <td>${article.created_at}</td>
                        <td><a href="javascript:void(0)" data-id="${article.id}" class="detail-article">Detail</a></td>
                        <td><a href="javascript:void(0)" data-id="${article.id}" class="edit-article">Edit</a></td>
                        <td><a href="javascript:void(0)" data-id="${article.id}" class="delete-article">Delete</a></td>
                    </tr>
                `);
            });
        }

        function showArticle(article, className) {
            let created_at_format = new Date(article.created_at);
            let updated_at_format = new Date(article.updated_at);
            var created_at = created_at_format.getFullYear() + "-" + ("0" + (created_at_format.getMonth() + 1)).slice(-2) + "-" + ("0" + created_at_format.getDate()).slice(-2) + "T" + ("0" + created_at_format.getHours()).slice(-2) + ":" + ("0" + created_at_format.getMinutes()).slice(-2) + ":" + ("0" + created_at_format.getSeconds()).slice(-2);
            var updated_at = updated_at_format.getFullYear() + "-" + ("0" + (updated_at_format.getMonth() + 1)).slice(-2) + "-" + ("0" + updated_at_format.getDate()).slice(-2) + "T" + ("0" + updated_at_format.getHours()).slice(-2) + ":" + ("0" + updated_at_format.getMinutes()).slice(-2) + ":" + ("0" + updated_at_format.getSeconds()).slice(-2);

            $(`.${className} #title`).val(article.title);
            $(`.${className} #body`).val(article.body);
            $(`.${className} #created-at`).attr('value', created_at);
            $(`.${className} #updated-at`).attr('value', created_at);
            $(`.edit-article-btn`).attr('data-id', article.id);

        }

        function removeValueFromArticle(className) {
            $(`.${className} #title`).val('');
            $(`.${className} #body`).val('');
            $(`.${className} #created-at`).val('');
            $(`.${className} #updated-at`).val('');
        }
    /** Eend Handle display */

    /** Handle event **/
        $(document).on("click", ".like-article", function() {
            let articleId = $(this).data("id");
            getData('/like/article/'+articleId+'.json', 'json', 'like-article');
        });

        $(document).on("click", ".article-manage", function() {
            $('.article-list').hide();
            $('.admin-article-add').hide();
            $('.admin-article-edit').hide();
            $('.admin-article-view').hide();
            $('.admin-article-list').show();
            $('.managment-articles').show();
            getArticlesByUser();
        });

        $(document).on("click", ".add-article-management", function() {
            $('.article-list').hide();
            $('.admin-article-list').hide();
            $('.admin-article-edit').hide();
            $('.admin-article-view').hide();
            $('.admin-article-add').show();
            getArticlesByUser();
        });

        $(document).on("click", ".add-article-btn", function() {
            let title = $(".admin-article-add #title").val();
            let body = $(".admin-article-add #body").val();
            let created_at = $(".admin-article-add #created-at").val();
            let updated_at = $(".admin-article-add #updated-at").val();
            if(title && body && created_at && updated_at) {
                let data = {
                    title,
                    body,
                    created_at,
                    updated_at
                }
                postData('/articles.json', 'POST', 'json', data);
                removeValueFromArticle('admin-article-add')
            } else {
                alert("Please check your data fields");
            }
        });

        $(document).on("click", ".edit-article-btn", function() {
            let title = $(".admin-article-edit #title").val();
            let body = $(".admin-article-edit #body").val();
            let created_at = $(".admin-article-edit #created-at").val();
            let updated_at = $(".admin-article-edit #updated-at").val();
            let articleId = $(this).data("id");

            if(title && body && created_at && updated_at) {
                let data = {
                    title,
                    body,
                    created_at,
                    updated_at
                }
                postData('/articles/'+articleId+'.json', 'PUT', 'json', data);
            } else {
                alert("Please check your data fields");
            }

        });

        $(document).on("click", ".delete-article", function() {
            let articleId = $(this).data("id");
            postData('/articles/'+articleId+'.json', 'DELETE', 'json', '', 'delete');
        });

        $(document).on("click", ".edit-article", function() {
            $('.admin-article-list').hide();
            $('.admin-article-add').hide();
            $('.admin-article-view').hide();
            $('.admin-article-edit').show();
            let articleId = $(this).data("id");
            getData('/articles/'+articleId+'.json', 'json', 'edit-article');
        });

        $(document).on("click", ".detail-article", function() {
            $('.admin-article-list').hide();
            $('.admin-article-add').hide();
            $('.admin-article-edit').hide();
            $('.admin-article-view').show();
            let articleId = $(this).data("id");
            getData('/articles/'+articleId+'.json', 'json', 'view-article');
        });

    /** end Handle event */

    /** Handle HTTP requests **/
        function getData(url, dataType, type) {
            $.ajax({
                url: url,
                method: 'GET',
                dataType: dataType,
                success: function(response) {
                    if(type == 'articles-list') {
                        displayArticles(response.data);
                    } else if(type == 'like-article') {
                        getArticles();
                    } else if(type == 'user-articles-list') {
                        displayArticlesByUser(response.data);
                    } else if(type == 'edit-article') {
                        showArticle(response.data, 'admin-article-edit');
                    } else if(type == 'view-article') {
                        showArticle(response.data, 'admin-article-view');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(status, error);
                }
            });
        }

        function postData(url, method, dataType, data) {
            let csrfToken = $('meta[name="csrfToken"]').attr('content');
            console.log(csrfToken);
            $.ajax({
                url: url,
                method: method,
                data:data,
                dataType: dataType,
                headers: {
                    'X-CSRF-Token': csrfToken
                },
                success: function(response) {
                    getArticlesByUser();
                    $('.admin-article-list').show();
                    $('.admin-article-add').hide();
                    $('.admin-article-edit').hide();
                },
                error: function(xhr, status, error) {
                    console.error(status, error);
                }
            });
        }
    /** End Handle HTTP requests */
});