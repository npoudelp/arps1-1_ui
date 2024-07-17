<?php
$page_title = "arps | Assisance";
$assistance = "active";
if ($_REQUEST['crop']) {
    $crop = $_REQUEST['crop'];
    $question = "Details on planting, growing and harvesting $crop";
}
include_once("../partials/header.php");
?>
<link rel="stylesheet" href="../css/loading.css">

<body>
    <?php
    include_once("../partials/navbar.php");
    ?>
    <div id="loading">
    </div>
    <div class="container-fluid my-5" id="assistContainer">
        <div class="row py-2">
            <div class="col-md-7 border-right">
                <p class="lead" id="answer"></p>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" class="form-control" id="question" placeholder="Enter your question here" autofocus>
                    <div class="input-group-append">
                        <button class="btn btn-outline-success" onclick="getAssiatance()" type="button" id="askButton">
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

    <?php
    include_once("../partials/footer.php");
    ?>
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

        let autoQuestion;

        getAssiatance = () => {
            let question;
            if ($("#question").val()) {
                question = $("#question").val();
            } else if (autoQuestion) {
                question = autoQuestion;
            } else {
                showError("Please enter a question");
                return;
            }
            $("#assistContainer, #footer").hide();
            $("#loading").css("top", "50%").css("left", "50%").css("position", "fixed").show();

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
                    answer = answer.replace(/\*\*(.*?)\*\*/g, "<br><span class='font-weight-bold'>$1</span>").replace(/\*/g, "<br>");
                    $("#answer").html(answer);
                    $("#loading").hide();
                    $("#assistContainer, #footer").show();
                    if (response.newquestion) {
                        addQuestionAnswer(question, answer);
                        setTimeout(() => {
                            $("#fqa").empty();
                            getFqa();
                        }, 2000);
                    }
                    if (!response.newquestion) {
                        let childrenHtml = $("#fqa").children().map(function() {
                            return $(this).text();
                        }).get();
                        childrenHtml.forEach((item, i) => {
                            if (item == autoQuestion || item == question) {
                                i++;
                                $("#fqa p").removeClass("border-success text-success");
                                $("." + i + "").addClass("border-success text-success");
                                console.log("match");
                            }
                            console.log(autoQuestion);
                        })
                    }
                },
                error: function(response, textStatus, errorThrown) {
                    if (response.status == 400) {
                        showError(response.responseJSON.error);
                    } else {
                        showError("An error occured");
                    }
                    $("#loading").hide();
                }
            });
        }

        let qna = {}

        getFqa = () => {
            let first_check = false;
            let id = $("#update").val();
            let latest_id = 0;
            const base_url = "http://127.0.0.1:8000/";
            let index = "1";
            $.ajax({
                url: base_url + 'api/frequent-questions/get/',
                type: 'GET',
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('access_token')
                },
                success: function(response) {
                    response.forEach((fqa) => {
                        qna[fqa.id] = fqa.answer;

                        $("#fqa").append(`<p onclick="getAnswer(${fqa.id})" id="${fqa.id}" class="lead border-bottom my-2 ${index}">${fqa.question}</p>`);
                        index++;
                        if (!first_check) {
                            latest_id = fqa.id;
                            $("#" + latest_id + "").addClass("border-success text-success");
                            first_check = true;
                        }

                        if (newLoad) {
                            let keys = Object.keys(qna);
                            let firstKey = keys[0];
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
            $("#fqa p").removeClass("border-success text-success");
            $("#" + id + "").addClass("border-success text-success");
        }
        newLoad = true;
        <?php
        if ($_REQUEST['crop']) {
        ?>
            autoQuestion = "<?php echo $question; ?>";
            getFqa();
            getAssiatance();
            <?php
        } else {
            ?>autoQuestion
            getFqa();
        <?php
        }
        ?>
    </script>

</body>

</html>