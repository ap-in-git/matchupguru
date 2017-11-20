 <div class="row">
  <div class="col-sm-12">
    <table class="table table-hover table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
                <th>Game</th>
            <th>Format</th>

            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($decks as $key => $deck)
            <tr id="deck_{{$deck->id}}">
              <td>{{$deck->id}}</td>
              <td>{{$deck->name}}</td>
              <td>{{$deck->game->name}}</td>
              <td>{{$deck->format->name}}</td>
                <td>
              <a href="{{route("admin.deck.edit",$deck->id)}}" class="btn btn-success">Manage deck</a>
              <a href="#" class="btn btn-danger" onclick="deletedeck({{$deck->id}})">Delete deck</a>
                </td>
            </tr>
          @endforeach
        </tbody>
      </table>
 {{$decks->links()}}
  </div>
</div>
