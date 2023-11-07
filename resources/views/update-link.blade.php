<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <form>
                    
                        <div class="form-sections">
                            <div class="container d-flex justify-content-between">
                                <div class="form-group w-40">
                                  <label>link</label>
                                  <input type="text" class="form-control add-link" placeholder="link">
                                </div>
                                <div class="form-group">
                                    <label>file</label>
                                    <input type="file" class="form-control add-file" >
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary add-more">Add More</button>
                        <button type="button" class="btn btn-primary submit">Submit</button>
                      </form>
                </div>
            </div>
        </div>
    </div>
    

    <script>
        $(document).ready(function(){
            $(document).on("click" ,".add-more" , function(e){
                let html = `<div class="container d-flex justify-content-between">
                                <div class="form-group w-40">
                                  <label>link</label>
                                  <input type="text" class="form-control add-link" placeholder="link">
                                </div>
                                <div class="form-group">
                                    <label>file</label>
                                    <input type="file" class="form-control add-file" >
                                </div>
                            </div>`;
            document.querySelector(".form-sections").insertAdjacentHTML( "beforeend",html);
            })

            /////////////////////////////////////////////////////////////////////////////////////////

            $(document).on("click", ".submit", async function (e) {
                e.stopImmediatePropagation();
                let links = [];
                let formContainers = document.querySelector(".form-sections").querySelectorAll(".container");
                let formData = new FormData();

                for (const container of formContainers) {
                    const linkTxt = container.querySelector(".add-link").value;
                    const file = container.querySelector(".add-file").files[0];

                    if (file) {
                        const dataUrl = await readFileAsync(file); // Wait for file reading to complete

                        links.push({
                            link: linkTxt,
                            file: dataUrl
                        });
                        console.log("if");
                    } else {
                        links.push({
                            link: linkTxt,
                            file: null,
                        });
                        console.log("else");
                    }
                }

                console.log(links);
                formData.append('_token', "{{csrf_token()}}");
                links.forEach(link => {
                    formData.append('links[]', link.link);
                    formData.append('files[]', link.file);
                });

                $.ajax({
                    type: "POST",
                    url: "{{url('api/update-profile')}}",
                    processData: false,
                    contentType: false,
                    data: formData,
                    success: function (res) {
                        console.log(res);
                    }
                });

                // Function to read a file asynchronously and return a Promise
                function readFileAsync(file) {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            resolve(event.target.result);
                        };
                        reader.onerror = (event) => {
                            reject(event.target.error);
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });
            /////////////////////////////////////////////////////////////////////////////////////////////////////////

        })
    </script>
</body>
</html>