<x-layout>
    <section id='section-edit-form'>
        <h1>Edit your offert</h1>
        <form action='/offerts/{{$offert->id}}' method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div>
                <label for='edit-name' >* name</label>
                <input type='text' name='name' id='edit-name' placeholder="Adam" value="{{$offert->name}}">
                @error('name')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='edit-surname' >* surname</label>
                <input type='text' name='surname' id='edit-surname' placeholder="Mickiewcz" value="{{$offert->surname}}">
                @error('surname')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='edit-voivodeship' >* voivodeship</label>
                <input type='text' name='voivodeship' id='edit-voivodeship' placeholder="Lubelskie" value="{{$offert->voivodeship}}">
                @error('voivodeship')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>            
                <label for='edit-city' >* city </label>
                <input type='text' name='city' id='edit-city' placeholder="Lublin" value="{{$offert->city}}">
                @error('city')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>            
                <label for='edit-company' > company </label>
                <input type='text' name='company' id='edit-company' placeholder="Barber 44" value="{{$offert->company}}">
                @error('company')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='edit-profession' >* Profession (comma spearated) </label>
                <input type='text' id='edit-profession' name='profession' placeholder="barber, hairdresser, cosmetic" value="{{$offert->profession}}">
                @error('profession')
                    <p>{{$message}}</p>
                @enderror
            </div>              
            <div>                    
                <label for='edit-workplace' >* Workplace (comma spearated) </label>
                <input type='text' id='edit-workplace' name='workplace' placeholder="salon, private, client" value="{{$offert->workplace}}">
            </div>  
            <div>                    
                <label for='edit-profile-picture' > Profile picture </label>
                <input type='file' id='edit-profile-picture' name='profile-picture' value="{{$offert['profile-picture']}}">
                <img src='{{$offert['profile-picture'] ? asset('storage/'. $offert['profile-picture']) : asset('images/no-image.jpg') }}' alt='pfp' width='150px' height='150px'>

            </div>  
            <div>
                <label for='edit-youtube' > youtube </label>
                <input type='text' name='youtube' id='edit-youtube' placeholder="https://www.youtube.com/@my_channel" value="{{$offert->youtube}}">
                @error('youtube')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='edit-facebook' > facebook </label>
                <input type='text' name='facebook' id='edit-facebook' placeholder="https://www.facebook.com/my_page" value="{{$offert->facebook}}">
                @error('facebook')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='edit-instagram' > instagram </label>
                <input type='text' name='instagram' id='edit-instagram' placeholder="https://www.instagram.com/my_profile" value="{{$offert->instagram}}">
                @error('instagram')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='edit-tiktok' > tiktok </label>
                <input type='text' name='tiktok' id='edit-tiktok' placeholder="https://www.tiktok.com/@my_profile" value="{{$offert->tiktok}}">
                @error('tiktok')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='edit-twitter' > twitter </label>
                <input type='text' name='twitter' id='edit-twitter' placeholder="https://twitter.com/my_profile"  value="{{$offert->twitter}}">
                @error('twitter')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <div>
                <label for='edit-description' > description </label>
                @php
                    $offert->description = str_replace("<br />", "", $offert->description);
                
                @endphp
                <textarea name='description' id='edit-description' cols='4' rows='5' >{!! $offert->description !!}</textarea>
                
                @error('description')
                    <p>{{$message}}</p>
                @enderror
            </div>
            <input type='submit' name='edit-submit' id='edit-submit' value='Update'>
        </form>
    </section>
</x-layout>