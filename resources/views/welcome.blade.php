<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Proximity Searching TEST</title>

        <!-- BOOTSTRAP -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
        <!-- JQUERY -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    </head>
    <body>
        <nav class="navbar navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Proximity Searching TEST</a>
            </div>
        </nav>

        <div class="container-fluid m-20">
            <div class="row justify-content-md-center">
                <div class="col-md-3 mt-5 form-group">
                    <label>You can search here</label>
                    <input type="text" class="form-control" name="search" id="search" placeholder="Keyword(s)">
                    <div class="d-grid gap-2 d-md-block">
                        <button type="button" id="btnSearch" class="btn btn-primary mt-3" style="width: 100%">
                            Search
                        </button>
                    </div>
                </div>
            </div>

            <div class="row justify-content-md-center" id="alertDiv" style="display: none">
                <div class="col-md-4 mt-5 mb-5 alert alert-danger">
                    You must enter a keyword!
                </div>
            </div>
            <div class="row justify-content-md-center" id="resultsDiv" style="display: none">
                <div class="col-md-6 mt-5 mb-5">
                    Results with term '<b><span id="termSpan"></span></b>':
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>City</th>
                                <th>State</th>
                            </tr>
                        </thead>
                        <tbody id="tBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
    <script>
        $("#btnSearch").click(() => {
            search();
        });

        $("#search").keyup(function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                search();
            }
        });

        let search = () => {
            $("#tBody").html('');   // Reset body content
            $("#alertDiv, #resultsDiv").hide(); // Reset html view
            let term = $("#search").val();
            if (!term.length) {
                $("#alertDiv").fadeIn('fast').delay('1700').fadeOut('fast');
                return false;
            }   // Validate Term input
            $("#termSpan").text(term);  // Bind term in span
            $("#resultsDiv").fadeIn('fast');
            $.ajax({
                url: '{{ route('search') }}',
                data: { term, _token: '{{ csrf_token() }}'},
                method: 'POST',
                success: (response) => {
                    let htmlResponse = '';
                    // Results
                    if(response.success && response['data'].length) {
                        response['data'].forEach((value) => {
                            htmlResponse += "<tr>";
                            htmlResponse += "<td>" + value['ID'] + "</td>";
                            htmlResponse += "<td>" + value['name'] + "</td>";
                            htmlResponse += "<td>" + value['city'] + "</td>";
                            htmlResponse += "<td>" + value['state'] + "</td>";
                            htmlResponse += "</tr>";
                        })  // Bind data
                    } else {
                        htmlResponse = "<tr><th colspan='4' class='text-center'> No results found </th></tr>";
                    }   // Bind 'No found' Message

                    $("#tBody").html(htmlResponse); // Bind HTML in body table
                }
            })
        }
    </script>
</html>
