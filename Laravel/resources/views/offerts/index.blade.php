<x-layout>
@include('partials/_search')

    @unless (count($offerts) == 0)
        @foreach ($offerts as $offert)
        <div class='offert'>
            <img src='{{$offert['profile-picture'] ? asset('storage/'. $offert['profile-picture']) : asset('images/no-image.jpg') }}' alt='pfp' width='150px' height='150px'>
            <ul class='offert-listing'>
                <li class='text-120'><a href='/offerts/{{$offert->id}}' >{{$offert->name}} {{$offert->surname}}</a></li>
                {{-- <li>{{$listing->surname}}</li> --}}
                <li><a href='/?profession={{$offert->profession}}'>{{$offert->profession}}</a></li>
                <li>{{$offert->voivodeship}}: {{$offert->city}}</li>
            </ul>
        </div>
        @endforeach
    @else
        <h1>Looks like there are no offerts...</h1>
        <h2>Log in and be the first one in here.</h2>
    @endunless

</x-layout>