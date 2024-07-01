<?php
$page_title = "arps | Field Management";
include_once("../partials/header.php");
?>
<link rel="stylesheet" href="../css/loading.css">

<body>
    <?php
    include_once("../partials/navbar.php");
    ?>
    <div id="loading"></div>
    <div class="container-fluid" id="assistContainer">
        <div class="row py-2">
            <div class="col-md-7 border-right">
                <p class="lead" id="answer"></p>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" class="form-control" id="question" placeholder="Enter your question here" autofocus>
                    <div class="input-group-append">
                        <button class="btn btn-outline-warning" onclick="getAssiatance()" type="button" id="askButton">
                            <i class="bi bi-send"></i>
                        </button>
                    </div>
                </div>
                <p class="lead my-2 font-weight-bold">Frequent Questions</p>

                <div class="" id="fqa">

                </div>
            </div>
        </div>
    </div>


    <script src="../js/tokenManager.js"></script>
    <script>
        addQuestionAnswer = (question, answer) => {
            const base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + "api/frequent-questions/add/",
                type: 'post',
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("access_token")
                },
                data: JSON.stringify({
                    question: question,
                    answer: answer
                }),
                contentType: "application/json",
                success: function(response) {
                    console.log(response);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }

        getAssiatance = () => {
            $("#askButton").attr("disabled", true);
            $("#assistContainer").hide();
            $("#loading").css("top", "50%").css("left", "50%").css("position", "fixed").show();

            let question = $("#question").val();
            const base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + "api/assistance/",
                type: 'post',
                headers: {
                    Authorization: "Bearer " + localStorage.getItem("access_token")
                },
                data: JSON.stringify({
                    question: question,
                }),
                contentType: "application/json",
                success: function(response) {
                    let answer = response.response;
                    answer = answer.replace(/\*\*/g, "<br>").replace(/:\*\*/g, ":\t").replace(/\*/g, "");
                    $("#answer").html(answer);
                    $("#loading").hide();
                    $("#assistContainer").show();
                    addQuestionAnswer(question, answer);
                    setTimeout(() => {
                        $("#fqa").empty();
                        getFqa();
                    }, 5000);
                },
                error: function(error) {
                    showError("An error occured, please try again later");
                    $("#loading").hide();
                }
            });
        }

        let qna = {}

        getFqa = () => {
            let id = $("#update").val();
            const base_url = "http://127.0.0.1:8000/";
            $.ajax({
                url: base_url + 'api/frequent-questions/get/',
                type: 'GET',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function(response) {
                    response.forEach((fqa) => {
                        qna[fqa.id] = fqa.answer;
                        $("#fqa").append(`<p onclick="getAnswer(${fqa.id})" class="lead border-bottom my-2">${fqa.question}</p>`);
                        if (newLoad) {
                            let keys = Object.keys(qna);
                            let firstKey = keys[0];
                            console.log(firstKey);
                            $("#answer").html(qna[firstKey]);
                            newLoad = false;
                        }
                    })
                }
            });
        }

        $("#question").keypress(function(event) {
            if (event.which == 13) {
                event.preventDefault();
                getAssiatance();
            }
        });

        getAnswer = (id) => {
            $("#answer").html(qna[id]);
        }
        newLoad = true;
        getFqa();
    </script>

</body>

</html>