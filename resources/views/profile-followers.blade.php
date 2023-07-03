<x-profile :sharedData="$sharedData">
    <div class="list-group">
      @foreach ($followers as $follow)
       <a href="/profile/{{$follow->userThatIsFollowing->username}}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{$follow->userThatIsFollowing->avatar}}" />
           {{$follow->userThatIsFollowing->username}}
        </a> 
      @endforeach
    
      </div>
  </x-profile>