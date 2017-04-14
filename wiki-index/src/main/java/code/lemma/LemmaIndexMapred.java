package code.lemma;

import java.io.IOException;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Vector;

import org.apache.hadoop.conf.Configuration;
import org.apache.hadoop.fs.Path;
import org.apache.hadoop.io.IntWritable;
import org.apache.hadoop.io.LongWritable;
import org.apache.hadoop.io.Text;
import org.apache.hadoop.mapreduce.Job;
import org.apache.hadoop.mapreduce.Mapper;
import org.apache.hadoop.mapreduce.lib.input.FileInputFormat;
import org.apache.hadoop.mapreduce.lib.input.KeyValueTextInputFormat;
import org.apache.hadoop.mapreduce.lib.output.FileOutputFormat;
import org.apache.hadoop.util.GenericOptionsParser;

import util.StringIntegerList;
import edu.umd.cloud9.collection.wikipedia.WikipediaPage;
import util.WikipediaPageInputFormat;
import code.lemma.Tokenizer;

/**
 * 
 *
 */
public class LemmaIndexMapred {
	public static class LemmaIndexMapper extends Mapper<LongWritable, WikipediaPage, Text, StringIntegerList> {

		@Override
		public void map(LongWritable offset, WikipediaPage page, Context context) throws IOException,
				InterruptedException {
			// TODO: implement Lemma Index mapper here
			if(page.isEmpty())return;
			Tokenizer tokenier=new Tokenizer();
			Text title=new Text();
			title.set(page.getDocid());
			String passage=page.getContent();

			List<String> content=tokenier.tokenize(passage);
			
			List<Integer> position=tokenier.position(passage, content);
			
			HashMap<String,ArrayList<Integer>> word_map=new HashMap<String,ArrayList<Integer>>();
			
			for(int i=0;i<position.size();i++){
				ArrayList<Integer> cur;
				String token=content.get(i);
				int pos_index=position.get(i);
				if(pos_index==-1)continue;
				if(word_map.containsKey(token)){
					cur=word_map.get(token);
					cur.add(pos_index);
					word_map.put(token, cur);
				}
				else{
					cur=new ArrayList<Integer>();
					cur.add(pos_index);
					word_map.put(token, cur);
				}
			}
			
		
			StringIntegerList stringIntegerList=new StringIntegerList(word_map);		
			context.write(title,stringIntegerList);	

		}
	}
	
	public static void main(String[] args) throws Exception{
		
		 Configuration conf = new Configuration();
		 GenericOptionsParser gop=new GenericOptionsParser(conf, args);
		 String[] otherArgs=gop.getRemainingArgs();
		    Job job = Job.getInstance(conf, "word count");
		    job.setJarByClass(LemmaIndexMapred.class);
		    job.setMapperClass(LemmaIndexMapper.class);
		    
		    job.setInputFormatClass(WikipediaPageInputFormat.class);
		    
			job.setOutputKeyClass(Text.class);
			job.setOutputValueClass(StringIntegerList.class);
		    

		    //job.setOutputFormatClass(OutputFormat.class);	 
		    FileInputFormat.addInputPath(job, new Path(otherArgs[0]));
		    FileOutputFormat.setOutputPath(job, new Path(otherArgs[1]));
		    System.exit(job.waitForCompletion(true) ? 0 : 1);
	}
}
