<div class="col-md-6">
    <form method="get" action="{{ route('user.userSearch') }}">
        <div class="input-group">
          <label class="sr-only">Search</label>
          <div class="input-group">
            <input wire:model.debounce.500ms="usersSearch" type="text" name="searchKey" class="form-control" placeholder="Search">
            <div class="input-group-append">
              <button type="submit" class="btn btn-primary">
                <span class="fas fa-search"></span></button>

            </div>
          </div>
        </div>
    </form>
    @if ($usersSearch)
      @if (count($users) > 0)
      <div class="list-group col-md-11 position-absolute" style="z-index:1">
      @foreach ($users as $user)
            <a href="{{url('/users')}}/{{$user->id}}/edit" class="list-group-item list-group-item-action"> {{ $user->name }} </a>
      @endforeach
      @else
      <a href"#" class="list-group-item list-group-item-action position-absolute" style="z-index:1"> No Results for {{ $usersSearch }} </a>
      @endif
      </div>
      @endif
</div>
