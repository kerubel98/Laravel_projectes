<x-layout>
    <x-slot:title>
        jobs
    </x-slot:title>
    <x-slot:heading>
        Contact page
    </x-slot:heading>
    @foreach($jobs as $job)
        <li>
            <a href="jobs/{{$job['id']}}"><strong>{{$job['title']}}</strong> : {{$job['salary']}}</a>
        </li>
    @endforeach
</x-layout>
