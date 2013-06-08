package ajou.database.nearby;

import android.os.Bundle;
import android.app.Activity;
import android.view.Menu;
import android.view.View;
import android.widget.TextView;

public class BlankActivity extends Activity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.blank_activity);
		
		findViewById(R.id.back).setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				// TODO Auto-generated method stub
				BlankActivity.this.finish();
			}
		});
		TextView textView = (TextView)this.findViewById(R.id.textView1);
		textView.setText("\n봉사활동정보");
		Bundle b = getIntent().getExtras();
		String title = b.getString("title");
		
		textView = (TextView)this.findViewById(R.id.textView2);
		textView.setText(title);
	}

	@Override
	public boolean onCreateOptionsMenu(Menu menu) {
		// Inflate the menu; this adds items to the action bar if it is present.
		getMenuInflater().inflate(R.menu.blank, menu);
		return true;
	}
}
