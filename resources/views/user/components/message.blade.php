<div class="flex-grow ml-56 p-8">
    @if (session('success'))
        <div class="flash-success">
            {{session('success')}}
        </div>
    @endif

    @if (session('remove'))
        <div class="flash-remove">
            {{session('remove')}}
        </div>
   @endif
</div>
