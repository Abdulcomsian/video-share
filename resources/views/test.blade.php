<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <title>Document</title>
    <style>
        .d-flex{
            display: flex;
        }
        .mx-2{
            margin: 5px 10px;
        }
        .success{
            background-color: green;
            color: white;
            padding: 10px 10px;
        }
        .w-50{
            width: 50%;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <input type="file" name="file"  class="w-50" multiple>
        <textarea name="text" id="" class="w-50"></textarea>
    </div>

    <div class="d-flex">
        <input type="file" name="file"  class="w-50">
        <textarea name="text" id="" class="w-50"></textarea>
    </div>

    <div class="d-flex">
        <input type="file" name="file"  class="w-50">
        <textarea name="text" id="" class="w-50"></textarea>
    </div>

    <div class="d-flex">
        <input type="file" name="file"  class="w-50">
        <textarea name="text" id="" class="w-50"></textarea>
    </div>

    <div>
        <button class="success" type="button">
            Test
        </button>
    </div>

    <script>

document.querySelector(".success").addEventListener("click", function(e) {
    let list = document.querySelectorAll(".d-flex");
    let form = new FormData();
    
    list.forEach((item, index) => {
        let fileInput = item.querySelector("input");
        let text = item.querySelector("textarea").value;
        let files = fileInput.files;
        for(let i=0; i<files.length; i++)
        {
                form.append(`data[${index}][file][${i}]` ,  files[i]);        
        }

        form.append(`data[${index}][text]`, text);
    });

    form.append("_token", "{{ csrf_token() }}");

    $.ajax({
        type: "post",
        url: "{{ route('test-data') }}",
        data: form,
        processData: false,
        contentType: false,
        success: function(res) {
            if (res.status) {
                toastr.success(res.msg);
                window.location.reload();
            } else {
                toastr.error(res.msg);
            }
        }
    });
});





    </script>


</body>
</html>