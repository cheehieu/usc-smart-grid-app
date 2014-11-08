package com.smartgrid;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ImageButton;

import com.parse.Parse;
import com.parse.ParseObject;
import com.parse.PushService;

public class USCSmartGridAppActivity extends Activity {
	ImageButton locationBtn;
	ImageButton comfortBtn;
	ImageButton forecastBtn;
	ImageButton reduceBtn;
	ImageButton forecast2Btn;
	
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.main);
        locationBtn = (ImageButton) findViewById(R.id.location_btn);
        comfortBtn = (ImageButton) findViewById(R.id.comfort_btn);
        forecastBtn = (ImageButton) findViewById(R.id.forecast_btn);
        reduceBtn = (ImageButton) findViewById(R.id.reduce_btn);
        forecast2Btn = (ImageButton) findViewById(R.id.forecast2_btn);
        Parse.initialize(this, "eTysCEKaA6217VvNgM903ohIlSp71RG8reQgPRvZ", "rZ7MwWzsAtrRRkhXthv4wX8tEOej4GwEYqjkvDx1");
        PushService.unsubscribe(this, "");
        PushService.subscribe(this, "", ReduceActivity.class);	//subscribe to global broadcast channel
        //create user as an item in SimpleDB for first-timers
        //check if on campus to route forecast activity to achartdroid
        buttonActions();
    }
    
    private void buttonActions() {
        locationBtn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
        		startActivity(new Intent(USCSmartGridAppActivity.this, LocationActivity.class));
			}
		});
        comfortBtn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
        		startActivity(new Intent(USCSmartGridAppActivity.this, ComfortActivity.class));
			}
		});
        forecastBtn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
        		startActivity(new Intent(USCSmartGridAppActivity.this, ForecastActivity.class));
			}
		});
        reduceBtn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
        		startActivity(new Intent(USCSmartGridAppActivity.this, ReduceActivity.class));
			}
		});
        forecast2Btn.setOnClickListener(new View.OnClickListener() {
			@Override
			public void onClick(View v) {
        		startActivity(new Intent(USCSmartGridAppActivity.this, Forecast2Activity.class));
			}
		});
    }
}