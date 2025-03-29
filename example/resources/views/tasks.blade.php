<x-layout>
    <x-slot:title>
        Task
    </x-slot:title>

    <x-slot:heading>
        Task Page
    </x-slot:heading>

    <h1>Welcome to the Task Page</h1>

    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3">
                    Title
                </th>
                <th scope="col" class="px-6 py-3">
                    Description
                </th>
                <th scope="col" class="px-6 py-3">
                    Priority
                </th>
                <th scope="col" class="px-6 py-3">
                    Status
                </th>
                <th scope="col" class="px-6 py-3">
                    User
                </th>
            </tr>
            </thead>
            <tbody>
            @foreach($tasks as $task)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                    <td class="px-6 py-4">
                        <a href="task/{{$task['id']}}">{{ $task['title'] }}</a>
                    </td>
                    <td class="px-6 py-4">
                       <a href="task/{{$task['id']}}">{{ $task['description'] }}</a>
                    </td>
                    <td class="px-6 py-4">
                      <a href="task/{{$task['id']}}">{{ $task['priority'] }}</a>
                    </td>
                    <td class="px-6 py-4">
                       <a href="task/{{$task['id']}}">{{ $task['status'] }}</a>
                    </td>
                    <td class="px-6 py-4">
                       <a href="task/{{$task['id']}}">{{ $task->user['name'] }}</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</x-layout>
