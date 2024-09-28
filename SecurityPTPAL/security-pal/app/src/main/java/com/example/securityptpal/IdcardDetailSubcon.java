package com.example.securityptpal;

import androidx.appcompat.app.AppCompatActivity;
import androidx.constraintlayout.widget.ConstraintLayout;

import android.Manifest;
import android.content.ActivityNotFoundException;
import android.content.Intent;
import android.content.pm.PackageManager;
import android.graphics.Bitmap;
import android.graphics.BitmapFactory;
import android.graphics.Canvas;
import android.graphics.Paint;
import android.graphics.pdf.PdfDocument;
import android.net.Uri;
import android.os.Build;
import android.os.Bundle;
import android.os.Environment;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.View;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.bumptech.glide.Glide;
import com.example.securityptpal.model.EmployeeSubcon;
import com.example.securityptpal.model.Subcon;
import com.google.android.material.floatingactionbutton.FloatingActionButton;
import com.muddzdev.styleabletoast.StyleableToast;

import java.io.File;
import java.io.FileOutputStream;
import java.io.IOException;
import java.util.function.BiFunction;

public class IdcardDetailSubcon extends AppCompatActivity {
    ImageView avatarview;
    TextView namedocsub,comdocsub,ttldocsub,divdocsub,depdocsub,posdocsub,iddocsub,phonedocsub,adddocsub,startdocsub,finishdocsub, locdocsub;
    ConstraintLayout constraintLayout;
    ImageButton save;
    EmployeeSubcon employeeSubcon;
    Subcon subcon;
    Bitmap bitmap, bitmap2, scaledbmp;
    FloatingActionButton fab, fab1, fab2;
    Animation fabOpen, fabClose, rotateForward, rotateBackward;
    boolean isOpen = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_idcard_detail_subcon);
        findId();
        viewData();
//        savePdf();

        fab = (FloatingActionButton) findViewById(R.id.main_employee_fab);
        fab1 = (FloatingActionButton) findViewById(R.id.main_employee_fab1);
        fab2 = (FloatingActionButton) findViewById(R.id.main_employee_fab2);

        fabOpen = AnimationUtils.loadAnimation(this, R.anim.fab_open);
        fabClose = AnimationUtils.loadAnimation(this, R.anim.fab_close);

        rotateForward = AnimationUtils.loadAnimation(this, R.anim.rotate_forward);
        rotateBackward = AnimationUtils.loadAnimation(this, R.anim.rotate_backward);

        fab.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                animateFab();
            }
        });

        fab1.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                Log.d("size",constraintLayout.getWidth()+" "+constraintLayout.getHeight());
                bitmap=loadBitmapFromView(constraintLayout,constraintLayout.getWidth(),constraintLayout.getHeight());
//                Log.d("size",linearLayout2.getWidth()+" "+linearLayout2.getHeight());
//                bitmap2=loadBitmapFromView2(linearLayout2,linearLayout2.getWidth(),linearLayout2.getHeight());
                createPdf();
            }
        });
        fab2.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                String foldername = "Import PDF";
                Intent intent = new Intent(Intent.ACTION_PICK);
                intent.setDataAndType(Uri.parse(Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DOCUMENTS) + File.separator + foldername), "*/*");
                startActivity(intent);
            }
        });
    }

    private void animateFab() {
        if (isOpen) {
            fab.startAnimation(rotateBackward);
            fab1.startAnimation(fabClose);
            fab2.startAnimation(fabClose);
            fab1.setClickable(false);
            fab2.setClickable(false);
            isOpen = false;
        } else {
            fab.startAnimation(rotateForward);
            fab1.startAnimation(fabOpen);
            fab2.startAnimation(fabOpen);
            fab1.setClickable(true);
            fab2.setClickable(true);
            isOpen = true;
        }
    }

//    private void savePdf() {
//        save.setOnClickListener(new View.OnClickListener() {
//            @Override
//            public void onClick(View v) {
//                Log.d("size",linearLayout.getWidth()+" "+linearLayout.getHeight());
//                bitmap=loadBitmapFromView(linearLayout,linearLayout.getWidth(),linearLayout.getHeight());
//                createPdf();
//            }
//        });
//    }

    private void createPdf() {
        DisplayMetrics displayMetrics=new DisplayMetrics();
        this.getWindowManager().getDefaultDisplay().getMetrics(displayMetrics);
        float height=displayMetrics.heightPixels;
        float width=displayMetrics.widthPixels;

        int convertHeight=(int)height,
                convertWidth=(int)width;

        PdfDocument document=new PdfDocument();
        Paint paint=new Paint();

        PdfDocument.PageInfo pageInfo=new PdfDocument.PageInfo.Builder(convertWidth,convertHeight,1).create();
        PdfDocument.Page page=document.startPage(pageInfo);
        Canvas canvas=page.getCanvas();
//        canvas.drawText("Page 1",40,50,paint);
        canvas.drawPaint(paint);
        bitmap=Bitmap.createScaledBitmap(bitmap,convertWidth,convertHeight,true);
        canvas.drawBitmap(bitmap,0,0,null);
        document.finishPage(page);

        PdfDocument.PageInfo pageInfo2=new PdfDocument.PageInfo.Builder(convertWidth,convertHeight,1).create();
        PdfDocument.Page page2=document.startPage(pageInfo2);
        Canvas canvas2=page2.getCanvas();
//        canvas2.drawText("Page 2",40,50,paint);
//        canvas2.drawPaint(paint);
//        bitmap2=Bitmap.createScaledBitmap(bitmap2,convertWidth,convertHeight,true);
//        canvas2.drawBitmap(bitmap2,0,0,null);
        canvas2.drawBitmap(scaledbmp,0,0,paint);
        document.finishPage(page2);

        //write document content
        String name = namedocsub.getText().toString();
//        String com = comdocsub.getText().toString();
        String targetPdf= Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DOCUMENTS) + File.separator + "Import PDF" + File.separator + "Subcon_" + name + ".pdf";
//        File filepath=new File(targetPdf);

        File filepath = new File(Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DOCUMENTS) + File.separator + "Import PDF");
        if (!filepath.exists()) {
            filepath.mkdirs();
        }

        FileOutputStream outputStream = null;

        try {
            outputStream = new FileOutputStream(targetPdf);
            document.writeTo(outputStream);
        }catch (IOException e){
            e.printStackTrace();
            StyleableToast.makeText(this, "something want wrong try again"+e.toString(), Toast.LENGTH_SHORT, R.style.resultfailed).show();
        }

        //close document
        document.close();
        StyleableToast.makeText(this, "pdf created successfully", Toast.LENGTH_SHORT, R.style.logsuccess).show();
//        openPdf();
    }

//    private void openPdf() {
//        File file=new File("/" + Environment.getExternalStorageDirectory() + File.separator + "page.pdf");
//        Intent intent=new Intent(Intent.ACTION_VIEW);
//        Uri uri=Uri.fromFile(file);
//        intent.setDataAndType(uri,"application/pdf");
//        intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
//        try {
//            startActivity(intent);
//        }catch (ActivityNotFoundException e){
//            Toast.makeText(this, "No application found for pdf reader", Toast.LENGTH_SHORT).show();
//        }
//    }

    private Bitmap loadBitmapFromView(ConstraintLayout linearLayout, int width, int height) {
        bitmap=Bitmap.createBitmap(width,height, Bitmap.Config.ARGB_8888);
        Canvas canvas=new Canvas(bitmap);
        linearLayout.draw(canvas);
        return bitmap;
    }

//    private Bitmap loadBitmapFromView2(LinearLayout linearLayout2, int width, int height) {
//        bitmap2=Bitmap.createBitmap(width,height, Bitmap.Config.ARGB_8888);
//        Canvas canvas2=new Canvas(bitmap2);
//        linearLayout2.draw(canvas2);
//        return bitmap2;
//    }

    private void viewData() {
        employeeSubcon = getIntent().getParcelableExtra("IDCARD1");
        subcon = getIntent().getParcelableExtra("IDCARD2");
//        Intent intent=getIntent();
//        byte[]bytes=intent.getByteArrayExtra("imageViewDocSubcon2");
//        Bitmap bitmap= BitmapFactory.decodeByteArray(bytes,0,bytes.length);
//        avatarview.setImageBitmap(bitmap);
//        String docsub_name=intent.getStringExtra("docsub_name");
//        String docsub_company=intent.getStringExtra("docsub_company");
//        String docsub_div=intent.getStringExtra("docsub_div");
//        String docsub_depart=intent.getStringExtra("docsub_depart");
//        String docsub_start=intent.getStringExtra("docsub_start");
//        String docsub_finish=intent.getStringExtra("docsub_finish");
//        String docsub_nip=intent.getStringExtra("docsub_nip");
//        String docsub_phone=intent.getStringExtra("docsub_phone");
//        String docsub_address=intent.getStringExtra("docsub_address");

        //set view
        namedocsub.setText(employeeSubcon.getName());
        ttldocsub.setText(": "+employeeSubcon.getTtl());
        iddocsub.setText(": "+employeeSubcon.getId());
        phonedocsub.setText(": "+employeeSubcon.getPhone());
        adddocsub.setText(": "+employeeSubcon.getAddress());
        comdocsub.setText(": "+subcon.getCompany());
        divdocsub.setText(": "+subcon.getDivision());
        depdocsub.setText(": "+subcon.getDepartment());
        posdocsub.setText(": "+employeeSubcon.getPosition());
        locdocsub.setText(": "+employeeSubcon.getLocation());
        startdocsub.setText(": "+employeeSubcon.getStart());
        finishdocsub.setText(": "+employeeSubcon.getFinish());
        Glide.with(this).load(employeeSubcon.getImg()).placeholder(R.drawable.pict).into(avatarview);
    }

    private void findId() {
        avatarview=(ImageView)findViewById(R.id.avatarview);
        namedocsub=(TextView)findViewById(R.id.namedocsub);
        comdocsub=(TextView)findViewById(R.id.comdocsub);
        ttldocsub=(TextView)findViewById(R.id.ttldocsub);
        iddocsub=(TextView)findViewById(R.id.iddocsub);
        phonedocsub=(TextView)findViewById(R.id.phonedocsub);
        startdocsub=(TextView)findViewById(R.id.startdocsub);
        finishdocsub=(TextView)findViewById(R.id.finishdocsub);
        adddocsub=(TextView)findViewById(R.id.adddocsub);
        divdocsub=(TextView)findViewById(R.id.divdocsub);
        depdocsub=(TextView)findViewById(R.id.depdocsub);
        posdocsub=(TextView)findViewById(R.id.posdocsub);
        locdocsub=(TextView)findViewById(R.id.locdocsub);
        constraintLayout=findViewById(R.id.lld);
        bitmap2 = BitmapFactory.decodeResource(getResources(), R.drawable.idcard);
        scaledbmp = Bitmap.createScaledBitmap(bitmap2,1080,2200,false);
//        linearLayout2=findViewById(R.id.lld2);
//        save=findViewById(R.id.save);
    }
}