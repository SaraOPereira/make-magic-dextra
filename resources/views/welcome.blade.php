<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Make Magic - Dextra</title>
        <link rel="stylesheet" href="{{ asset('css/app.css')}}" media="print" onload="this.media='all';this.onload=null;" />
        <noscript><link rel="stylesheet" href="{{ asset('css/app.css')}}" ></noscript>
    </head>
    <body>
        <section id="home">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid logo">
                    </div>
                    <div class="col-md-12">
                        <h1>Register your Harry Potter character!</h1>
                        <h2>Choose a name, house and school for you!</h2>
                        <form action="{{ route('sendData') }}" method="post">
                            @csrf
                            <div class="flex">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input id="name" class="form-control" type="text" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <input id="role" class="form-control" type="text" name="role" required>
                                </div>
                                <div class="form-group">
                                    <label for="school">School</label>
                                    <input id="school" class="form-control" type="text" name="school" required>
                                </div>
                                <div class="form-group">
                                    <label for="house">House</label>
                                    <select name="house" id="house" class="form-control" required>
                                        <option value="">Select a house</option>
                                        @for($i = 0; $i < count($houses); $i++)
                                            <option value="{{ $houses[$i]->id }}">{{ $houses[$i]->name }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="patronus">Patronus</label>
                                    <input id="patronus" class="form-control" type="text" name="patronus" required>
                                </div>
                            </div>
                            <input type="text" name="id" hidden>
                            <div class="btn-group">
                                <button id="submit" type="submit">Send character</button>
                                <button id="clean">Clean fields</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        @if($json != "" && count($json['characters'])!= 0)
            <section id="characters">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <h1>See all the characters send to us!</h1>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">School</th>
                                            <th scope="col">House</th>
                                            <th scope="col">Patronus</th>
                                            <th scope="col">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for($j = 0; $j < count($json['characters']); $j++)
                                            <tr>
                                                <th scope="row">{{ $j+1 }}</th>
                                                <td>{{ $json['characters'][$j]['name'] }}</td>
                                                <td>{{ $json['characters'][$j]['role'] }}</td>
                                                <td>{{ $json['characters'][$j]['school'] }}</td>
                                                <td>{{ $json['characters'][$j]['house'] }}</td>
                                                <td>{{ $json['characters'][$j]['patronus'] }}</td>
                                                <td>
                                                    <button class="btnEdit" value="{{ $j }}">Edit</button>
                                                    <button class="btnDelete" value="{{ $j }}">Delete</button>
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <p>Desenvolvido por Sara Pereira</p>
                    </div>
                </div>
            </div>
        </footer>
    </body>
    <script>
        var btnClean = document.querySelector("form button#clean");
        var inputs = document.querySelectorAll('form input');
        var select = document.querySelector("form select");
        btnClean.addEventListener("click", function(e){
            e.preventDefault();
            for(var i = 0; i < inputs.length; i ++){
                inputs[i].value = "";
            }
            select.value = "";
        });
        var btnEdit = document.getElementsByClassName("btnEdit");
        for(var i = 0; i < btnEdit.length; i++){
            btnEdit[i].addEventListener("click", function(e){
                var url = '/searchCharacter/' + this.value;
                let xhr = new XMLHttpRequest();
                xhr.open('GET', url);
                xhr.send();
                xhr.onreadystatechange = () => {
                    if(xhr.readyState == 4) {
                        result = JSON.parse(xhr.responseText);
                        document.querySelector("input[name=name]").value = result.name;
                        document.querySelector("input[name=role]").value = result.role;
                        document.querySelector("input[name=school]").value = result.school;
                        document.querySelector("input[name=patronus]").value = result.patronus;
                        document.querySelector("input[name=id]").value = this.value;
                        select.value = result.house;
                    }
                };
            });
        }
        var btnDelete = document.getElementsByClassName('btnDelete');
        for(var j = 0; j < btnDelete.length; j++){
            btnDelete[j].addEventListener('click', function(e){
                if (confirm('Are you sure you want to delete this character?')) {
                    var url = '/deleteCharacter/' + this.value;
                    window.location.pathname = url;
                }
            });
        }
    </script>
</html>