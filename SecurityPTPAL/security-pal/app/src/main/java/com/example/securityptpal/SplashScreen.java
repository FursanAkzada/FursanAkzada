package com.example.securityptpal;

import androidx.appcompat.app.AppCompatActivity;
import androidx.core.view.GravityCompat;

import android.content.Intent;
import android.media.MediaPlayer;
import android.os.Bundle;
import android.os.Handler;
import android.view.View;
import android.view.WindowManager;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.Button;
import android.widget.ImageView;

public class SplashScreen extends AppCompatActivity {

    private static int SPLASH_TIME_OUT = 3000;
    View third, fourth, five;
    Button lets;

    Animation topAnimation, middleAnimation, middle2Animation, bottomAnimation;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getWindow().setFlags(WindowManager.LayoutParams.FLAG_FULLSCREEN, WindowManager.LayoutParams.FLAG_FULLSCREEN);
        setContentView(R.layout.activity_splash_screen);

        MediaPlayer mediaPlayer = MediaPlayer.create(getApplicationContext(), R.raw.ringtone);
        mediaPlayer.start();

        topAnimation = AnimationUtils.loadAnimation(this,R.anim.top_animation);
        middleAnimation = AnimationUtils.loadAnimation(this,R.anim.middle_animation);
        middle2Animation = AnimationUtils.loadAnimation(this,R.anim.middle2_animation);
        bottomAnimation = AnimationUtils.loadAnimation(this,R.anim.bottom_animation);

        third = findViewById(R.id.third);
        fourth = findViewById(R.id.fourth);
        five = findViewById(R.id.five);
        lets = findViewById(R.id.bt_lets);

        third.setAnimation(middle2Animation);
        fourth.setAnimation(middleAnimation);
        five.setAnimation(middleAnimation);
        lets.setAnimation(bottomAnimation);

        new Handler().postDelayed(new Runnable() {
            @Override
            public void run() {
//                Intent intent = new Intent(splash.this, login.class);
//                startActivity(intent);
//                finish();
                lets.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        Intent intent = new Intent(SplashScreen.this, MainActivity.class);
                        startActivity(intent);
                        finish();
                    }
                });
                mediaPlayer.stop();
            }
        },SPLASH_TIME_OUT);

    }
}