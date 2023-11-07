/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import jQuery from "jquery";
window.$ = jQuery;

$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

// attributes
// add
$(document).on("click", "#linkAttributes", function (event) {
    if (localStorage.getItem("iteratorClick") === null) {
        localStorage.setItem("iteratorClick", 1);
    }

    if (localStorage.getItem("iteratorClick") == 4) {
        $(".app-dynamic-page").addClass("visually-hidden");
        localStorage.clear();
        localStorage.removeItem("iteratorClick");
    }

    $("#attributes").clone(true).insertBefore(event.target);
    $("#attributes").removeClass("visually-hidden");
    $(".app-dynamic-page").css({ height: "+=50" });
    let counter = localStorage.getItem("iteratorClick");
    localStorage.setItem("iteratorClick", ++counter);
});

// remove
$(document).on("click", ".del", function (event) {
    $(event.target).closest("#attributes").remove();
    $(".app-dynamic-page").css({ height: "-=50" });
    let counter = localStorage.getItem("iteratorClick");
    localStorage.setItem("iteratorClick", --counter);
});

//pages visually
$(document).on("click", "#createProduct", function (event) {
    $(".app-dynamic-page").removeClass("visually-hidden");

    let create_product_html = `

    <div class="create-container-form mt-3">

        <div class="row">
            <h3 class="name-form col-10">Добавить продукт</h3>
            <a id="closedCreateForm" class="col">
                <img class="icon-closed-create-form" src="svg/Close_round.svg" alt="dots icon">
            </a>
        </div>
        <form id="createForm">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="POST">
            <div class="form-group col-9">
                <label for="article" class="col-form-label">Артикул</label>
                <input id="article" class="form-control" name="article"
                    value="" required>
            </div>
            <div class="form-group col-9">
                <label for="name" class="col-form-label">Название</label>
                <input id="name" type="name" class="form-control"
                    name="name" value="" required>
            </div>
            <div class="form-group col-9">
                <label for="status" class="col-form-label">Статус</label>
                <select id="status" class="form-select {{ $errors->has('status') ? ' is-invalid' : '' }}" name="status">
                    <option value="available">Доступен</option>
                    <option value="unavailable">Недоступен</option>
                </select>
            </div>
            <h3 class="header-attributes mt-3">Аттрибуты</p>
            <div class="form-group row g-3 mt-3 visually-hidden" id="attributes">
                <div class="col-auto">
                    <label for="key" class="col-form-label name-attributes">Название</label>
                    <input name="keys[]"type="name" id="key" class="form-control">
                </div>
                <div class="col-auto">
                    <label for="value" class="col-form-label name-attributes">Значение</label>
                    <input name="values[]" type="name" class="form-control" id="value">
                </div>
                <div class="col-auto">
                    <a class="btn mt-4 del">
                        <img class="icon-closed" src="svg/Vector (Stroke).svg" alt="dots icon">
                    </a>
                </div>
            </div>
            <a type="button" class="" id="linkAttributes">
                 <img class="icon-add-attributes" src="svg/add_attributes.svg" alt="dots icon">
            </a>

            <div class="form-group mt-3">
                <button type="submit" class="btn btn-info send-button">Добавить</button>
            </div>
        </form>
    </div>`;

    // Вставка html в «page-content» нашего приложения
    $(".app-dynamic-page").html(create_product_html);

    // send-create-form-post
    document
        .getElementById("createForm")
        .addEventListener("submit", function (event) {
            event.preventDefault();
            var form_data = new FormData(event.target);

            const values = extractArrayFromInput("#value");
            const keys = extractArrayFromInput("#key");

            $.ajax({
                type: "POST",
                url: "/api/product",
                data: {
                    article: form_data.get("article"),
                    name: form_data.get("name"),
                    status: form_data.get("status"),
                    keys: keys,
                    values: values,
                },
                dataType: "json",
                // contentType: "application/json",

                success: (response) => {
                    localStorage.setItem(response.article, response);
                    // flash
                    $(".app-dynamic-page").append(
                        `<div id="flash-api" class="alert alert-success">
                              <p id="flash"></p>
                          </div>`
                    );

                    $("#flash").html(
                        "Создан успешно продукт! Проверьте почту! Чтобы перейти в продукт"
                    );
                },
                error: (jqXHR) => {
                    $(".app-dynamic-page").append(
                        `<div id="flash-api" class="alert alert-danger">
                              <p id="flash"></p>
                          </div>`
                    );

                    $("#flash").html(jqXHR.responseJSON.message);
                },
            });
            // return false;
        });
});

$(document).on("click", "#showProduct", function (event) {
    event.preventDefault();
    let article = event.currentTarget.innerText;
    $.getJSON("/api/product/" + article, (product) => {
        $(".app-dynamic-page").removeClass("visually-hidden");

        // const data = product.data.map((value, key) => `${key}: ${value}`);
        // console.log(data);

        let create_post_html =
            `
            <div class="card-show-product">

                    <h3 class="name-form name-form-show col-7 mt-3 ms-4">` +
            product.name +
            `</h3>

                <div class="row">
                    <div class="d-flex flex-row col-1 icon-group">
                        <a id="editForm" class="col">
                            <img class="icon-edit" src="svg/edit_product.svg" alt="dots icon">
                        </a>
                        <a id="destroyForm" class="col">
                            <img class="icon-destroy" src="svg/garbage.svg" alt="dots icon">
                        </a>
                    </div>
                    <a id="closedCreateForm" class="col">
                        <img class="icon-closed-show-form" src="svg/Close_round.svg" alt="dots icon">
                    </a>
                </div>

                <table class="table table-product">
                    <tbody>
                        <tr>
                            <td class="data-name">Артикул</td>
                            <td class="data-value">` +
            product.article +
            `</td>
                        </tr>
                        <tr>
                            <td class="data-name">Название</td>
                            <td class="data-value">` +
            product.name +
            `</td>
                        </tr>
                        <tr>
                            <td class="data-name">Статус</td>
                            <td class="data-value">` +
            getStatus(product.status) +
            `</td>
                        </tr>
                        <tr>
                            <td class="data-name">Статус</td>
                            <td class="data-value">` +
            extractArray(product.data) +
            `</td>
                        </tr>
                    </tbody>
                </table>

            </div> `;
        $(".app-dynamic-page").html(create_post_html);

        $(document).on("click", "#destroyForm", function (event) {
            $.ajax({
                type: "DELETE",
                url: "/api/product/" + product.article,
                data: {
                    // article: form_data.get("article"),
                    // name: form_data.get("name"),
                    // status: form_data.get("status"),
                    // keys: keys,
                    // values: values,
                },
                dataType: "json",
                // contentType: "application/json",

                success: (response) => {
                    localStorage.setItem(response.article, response);
                    // flash
                    $(".app-dynamic-page").append(
                        `<div id="flash-api" class="alert alert-success">
                              <p id="flash"></p>
                          </div>`
                    );

                    $("#flash").html("Продукт успешно удален!");
                },
                error: (jqXHR) => {
                    $(".app-dynamic-page").append(
                        `<div id="flash-api" class="alert alert-danger">
                              <p id="flash"></p>
                          </div>`
                    );

                    $("#flash").html(jqXHR.responseJSON.message);
                },
            });
            // return false;
        });

        $(document).on("click", "#editForm", function (event) {
            $(".card-show-product").addClass("visually-hidden");
            let edit_product_html =
                `

    <div class="edit-container-form mt-3">

        <div class="row">
            <h3 class="name-form-edit col-10">Редактировать ` +
                product.name +
                ` </h3>
            <a id="closedCreateForm" class="col">
                <img class="icon-closed-create-form" src="svg/Close_round.svg" alt="dots icon">
            </a>
        </div>
        <form id="edit-form">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="POST">
            <div class="form-group col-9">
                <label for="article" class="col-form-label">Артикул</label>
                <input id="article" class="form-control" name="article" value="` +
                product.article +
                `"
                     required>
            </div>
            <div class="form-group col-9">
                <label for="name" class="col-form-label">Название</label>
                <input id="name" type="name" class="form-control"
                    name="name" value="` +
                product.name +
                `" required>
            </div>
            <div class="form-group col-9">
                <label for="status" class="col-form-label">Статус</label>
                <select id="status" class="form-select {{ $errors->has('status') ? ' is-invalid' : '' }}" name="status">
                    <option value="` +
                product.status +
                `">` +
                getStatus(product.status) +
                `</option>
                    <option value="unavailable">Не доступен</option>
                </select>
            </div>
            <h3 class="header-attributes mt-3">Аттрибуты</p>
            <div class="form-group row g-3 mt-3 visually-hidden" id="attributes">
                <div class="col-auto">
                    <label for="key" class="col-form-label name-attributes">Название</label>
                    <input name="keys[]"type="name" id="key" class="form-control">
                </div>
                <div class="col-auto">
                    <label for="value" class="col-form-label name-attributes">Значение</label>
                    <input name="values[]" type="name" class="form-control" id="value">
                </div>
                <div class="col-auto">
                    <a class="btn mt-4 del">
                        <img class="icon-closed" src="svg/Vector (Stroke).svg" alt="dots icon">
                    </a>
                </div>
            </div>
            <a type="button" class="" id="linkAttributes">
                 <img class="icon-add-attributes" src="svg/add_attributes.svg" alt="dots icon">
            </a>
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-info send-button">Сохранить</button>
            </div>
        </form>
    </div>`;

            // Вставка html в «page-content» нашего приложения
            $(".app-dynamic-page").html(edit_product_html);

            // send-create-form-post
            document
                .getElementById("edit-form")
                .addEventListener("submit", function (event) {
                    event.preventDefault();
                    var form_data = new FormData(event.target);

                    const values = extractArrayFromInput("#value");
                    const keys = extractArrayFromInput("#key");

                    $.ajax({
                        type: "PUT",
                        url: "/api/product/" + product.article,
                        data: {
                            article: form_data.get("article"),
                            name: form_data.get("name"),
                            status: form_data.get("status"),
                            keys: keys,
                            values: values,
                        },
                        dataType: "json",
                        // contentType: "application/json",

                        success: (response) => {
                            localStorage.setItem(response.article, response);
                            // flash
                            $(".app-dynamic-page").append(
                                `<div id="flash-api" class="alert alert-success">
                              <p id="flash"></p>
                          </div>`
                            );

                            $("#flash").html("Продукт успешно изменен!");
                        },
                        error: (jqXHR) => {
                            $(".app-dynamic-page").append(
                                `<div id="flash-api" class="alert alert-danger">
                              <p id="flash"></p>
                          </div>`
                            );

                            $("#flash").html(jqXHR.responseJSON.message);
                        },
                    });
                    // return false;
                });
        });
    }).fail(function (data) {
        alert(data["message"]);
    });
});

// edit form
// create - form - closed;
$(document).on("click", "#closedCreateForm", function (event) {
    $(".app-dynamic-page").addClass("visually-hidden");
});

// helpers
function getStatus(status) {
    return status === "available" ? "Доступен" : "Не доступен";
}

function extractArrayFromInput(selector) {
    return Array.from(document.querySelectorAll(`${selector}`), (v) => v.value);
}

function extractArray(obj) {
    let arrayOfArrays = Object.entries(obj);
    let arrayOfStrings = arrayOfArrays.map((item) => item.join(":"));
    return arrayOfStrings.join("<br />");
}
