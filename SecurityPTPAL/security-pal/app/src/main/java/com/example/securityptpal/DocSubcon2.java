package com.example.securityptpal;

import androidx.annotation.NonNull;
import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;

import android.Manifest;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.BitmapFactory;
import android.net.Uri;
import android.os.Bundle;
import android.provider.MediaStore;
import android.view.View;
import android.widget.ImageView;
import android.widget.Toast;

import com.muddzdev.styleabletoast.StyleableToast;
import com.zolad.zoominimageview.ZoomInImageView;

import java.io.FileNotFoundException;
import java.io.InputStream;

public class DocSubcon2 extends AppCompatActivity {
    private static final int SELECT_PHOTO = 100;
    ZoomInImageView imageView;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_doc_subcon2);
        imageView = findViewById(R.id.imageViewDocSubcon2);
    }

    public void openGallery(View view) {
        ActivityCompat.requestPermissions(
                DocSubcon2.this,
                new String[]{Manifest.permission.READ_EXTERNAL_STORAGE},
                SELECT_PHOTO
        );
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {

        if(requestCode == SELECT_PHOTO) {
            if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                Intent intent = new Intent(Intent.ACTION_PICK);
                intent.setType("image/*");
                startActivityForResult(intent, SELECT_PHOTO);
            } else {
                StyleableToast.makeText(getApplicationContext(), "You don't have permission to access file location!", Toast.LENGTH_SHORT, R.style.warning).show();
            }
            return;
        }
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode,@Nullable Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

        if (requestCode == SELECT_PHOTO) {
            if (resultCode == RESULT_OK) {
                Uri selectImage = data.getData();
                InputStream inputStream = null;

                try {
                    assert selectImage != null;
                    inputStream = getContentResolver().openInputStream(selectImage);
                } catch (FileNotFoundException e) {
                    e.printStackTrace();
                }
                BitmapFactory.decodeStream(inputStream);
                imageView.setImageURI(selectImage);
            }
        }
    }
}