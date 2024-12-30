<x-layout>
    <section id='section-create-form'>
        <h1>Add your offert</h1>
        <form action='/offerts' method="post" enctype="multipart/form-data">
            @csrf
            <div>
                <label for='create-name' >* name</label>
                <input type='text' name='name' id='create-name' placeholder="Adam" value="{{old('name')}}">
                @error('name')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='create-surname' >* surname</label>
                <input type='text' name='surname' id='create-surname' placeholder="Mickiewcz" value="{{old('surname')}}">
                @error('surname')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='create-voivodeship' >* voivodeship</label>
                <input type='text' name='voivodeship' id='create-voivodeship' placeholder="Lubelskie" value="{{old('voivodeship')}}">
                @error('voivodeship')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>            
                <label for='create-city' >* city </label>
                <input type='text' name='city' id='create-city' placeholder="Lublin" value="{{old('city')}}">
                @error('city')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>            
                <label for='create-company' > company </label>
                <input type='text' name='company' id='create-company' placeholder="Barber 44" value="{{old('company')}}">
                @error('company')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='create-profession' >* Profession (comma spearated) </label>
                <input type='text' id='create-profession' name='profession' placeholder="barber, hairdresser, cosmetic" value="{{old('profession')}}">
                @error('profession')
                    <p>{{$message}}</p>
                @enderror
            </div>              
            <div>                    
                <label for='create-workplace' >* Workplace (comma spearated) </label>
                <input type='text' id='create-workplace' name='workplace' placeholder="salon, private, client" value="{{old('workplace')}}">
                @error('workplace')
                    <p>{{$message}}</p>
                @enderror
            </div>  
            <div>                    
                <label for='create-profile-picture' > Profile picture </label>
                <input type='file' id='create-profile-picture' name='profile-picture' value="{{old('profile-picture')}}">
                {{-- <img src='{{$offert['profile-picture'] ? asset('storage/'. $offert['profile-picture']) : asset('images/no-image.jpg') }}' alt='pfp' width='150px' height='150px'> --}}
                @error('profile-picture')
                    <p>{{$message}}</p>
                @enderror
            </div>  
            <div>
                <label for='create-youtube' > youtube </label>
                <input type='text' name='youtube' id='create-youtube' placeholder="https://www.youtube.com/@my_channel" value="{{old('youtube')}}">
                @error('youtube')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='create-facebook' > facebook </label>
                <input type='text' name='facebook' id='create-facebook' placeholder="https://www.facebook.com/my_page" value="{{old('facebook')}}">
                @error('facebook')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='create-instagram' > instagram </label>
                <input type='text' name='instagram' id='create-instagram' placeholder="https://www.instagram.com/my_profile" value="{{old('instagram')}}">
                @error('instagram')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='create-tiktok' > tiktok </label>
                <input type='text' name='tiktok' id='create-tiktok' placeholder="https://www.tiktok.com/@my_profile" value="{{old('tiktok')}}">
                @error('tiktok')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='create-twitter' > twitter </label>
                <input type='text' name='twitter' id='create-twitter' placeholder="https://twitter.com/my_profile"  value="{{old('twitter')}}">
                @error('twitter')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='create-description' > description </label>
                <textarea name='description' id='create-description' cols='4' rows='5'>{{old('description')}}</textarea>
                @error('description')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <input type='submit' name='create-submit' id='create-submit' value='Post'>
        </form>
    </section>
</x-layout>