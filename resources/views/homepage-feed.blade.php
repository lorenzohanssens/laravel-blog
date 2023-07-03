<x-layout>
    <div class="container py-md-5 container--narrow">
      @unless($feed->isEmpty())
      <h2 class="text-center mb-4">Latest from your following</h2>
      @foreach ($feed as $post)
    <x-posts :post="$post">
    </x-posts>
    @endforeach
    
    <div class="mt-4">
      {{$feed->links()}}
      </div>
      @else
      <div class="text-center">
        <h2>Hello <strong>{{auth()->user()->username}}</strong>, your feed is empty.</h2>
        <p class="lead text-muted">Your feed displays the latest posts from the people you follow. If you don&rsquo;t have any friends to follow that&rsquo;s okay; you can use the &ldquo;Search&rdquo; feature in the top menu bar to find content written by people with similar interests and then follow them.</p>
      </div>
      @endunless 
        
      </div>
</x-layout>