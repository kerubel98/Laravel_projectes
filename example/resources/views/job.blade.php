<x-layout>
    <x-slot:title>
        jobs
    </x-slot:title>
    <x-slot:heading>
        jobs page
    </x-slot:heading>
    <h1 class="font-bold text-lg">{{$job['title']}}</h1>
    <p>This job pays {{$job['salary']}}</p>
</x-layout>
