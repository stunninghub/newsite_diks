@extends('0000000000')
@section('title')
Posts
@endsection
@section('content')
<form action="" id="save_post_form">
    @csrf
    <div class="container">
        <div class="form-group">
            <select name="select" id="select_id">
                <option value="">Add new</option>
                @foreach($all_posts as $post)
                <option value="{{ $post->id }}">{{ $post->id }} => {{ $post->name }}</option>
                @endforeach
            </select>
        </div><br>
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name">
        </div><br>
        <div class="form-group">
            <label for="name">description:</label>
            <input type="text" name="description" id="description">
        </div><br>
        <div class="form-group">
            <button type="submit" name="save" id="save">Save</button>
        </div>
    </div>
</form>
<table class="post_data_rows">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($all_posts as $post)
        <tr>
            <td>{{ $post->id }}</td>
            <td>{{ $post->name }}</td>
            <td>{{ $post->description }}</td>
            <td><button class="remove_post" data-id="{{ $post->id }}">&times;</button></td>
        </tr>
        @endforeach
    </tbody>
</table>
<script>
    $(document).on('submit', '#save_post_form', function(e) {
        e.preventDefault();
        let this_form = $(this);
        let post_data = new FormData(this);
        $.ajax({
            url: "/add_post",
            type: "POST",
            data: post_data,
            cache: false,
            processData: false,
            contentType: false
        }).done((res) => {
            console.log(res);
            let post_id_val = res.post_id;
            if ($('#select_id') == "") {
                this_form.get(0).reset();
                $('#select_id').append(`<option value="${post_id_val}">${post_id_val} =>. ${res.name}</option>`);
                $('.post_data_rows tbody').append(`
            <tr>
                <td>${post_id_val}</td>
                <td>${res.name}</td>
                <td>${res.desc}</td>
                <td><button class="remove_post" data-id="${post_id_val}">&times;</button></td>
            </tr>
            `);
            }
        }).fail((err) => {
            console.log(err);
        })
    });

    $('#select_id').on('change', function() {
        let postid = $(this).val();
        $.ajax({
            url: "/get_post_data",
            type: "POST",
            data: {
                post_id: postid
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name=csrf_token]').attr('content'),
            }
        }).done((res) => {
            $('input[name=name]').val(res.name);
            $('input[name=description]').val(res.description);
        }).fail((err) => {
            console.log(err);
        })
    });

    $(document).on('click', '.remove_post', function() {
        let this_post = $(this);
        var post_id = $(this).attr('data-id');
        $.ajax({
            url: "/delete_post",
            type: "POST",
            data: {
                post_id: post_id
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name=csrf_token]').attr('content'),
            }
        }).done((res) => {
            if (res) {
                this_post.parent().parent().remove();
                $('#select_id option').each(function(ind, itm) {
                    if ($(itm).val() == post_id) {
                        $(itm).remove();
                    }
                });
            }
        }).fail((err) => {
            console.log(err);
        })
    })
</script>
@endsection