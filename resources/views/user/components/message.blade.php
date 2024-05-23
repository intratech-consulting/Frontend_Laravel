<style>
    .flash-success {
        padding: 20px;
        background-color: #33a43a;
        color: white;
        margin: 20px 0px;
        border-radius: 4px;
    }

    .flash-error {
        padding: 20px;
        background-color: rgb(235, 47, 47);
        color: white;
        margin: 20px 0px;
        border-radius: 4px;
    }

    .flash-remove{
        padding: 20px;
        background-color: #FF5733;
        color: white;
        margin: 20px 0px;
        border-radius: 4px;
    }
</style>

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
