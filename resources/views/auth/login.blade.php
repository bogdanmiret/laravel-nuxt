@extends('layouts.app')

@section('content')
<v-row
    align="center"
    justify="center"
>
    <v-col class="shrink"
        cols="12"
        sm="8"
        md="4"
    >
        <v-card class="elevation-12">
            <v-toolbar
            color="primary"
            dark
            flat
            >
                <v-toolbar-title>Login</v-toolbar-title>
                <v-spacer />
            </v-toolbar>
             <v-card-subtitle>
                @error('email')
                    <v-alert
                    dense
                    outlined
                    type="error"
                    >
                    {{ $message }}
                    </v-alert>
                @enderror
                @error('password')
                    <v-alert
                    dense
                    outlined
                    type="error"
                    >
                    {{ $message }}
                    </v-alert>
                @enderror
             </v-card-subtitle>
            <v-card-text>
                <form method="POST" action="{{ route('login') }}">
                    <div>
                        @csrf
                        <v-text-field label="Email" name="email" type="email" hide-details="auto"></v-text-field>
                        <v-text-field label="Password" name="password" type="password"></v-text-field>
                        <v-spacer />
                        <v-btn type="submit" color="primary">Login</v-btn>
                    </div>
                </form>
            </v-card-text>
        </v-card>
    </v-col>
</v-row>
@endsection
