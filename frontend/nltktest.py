import nltk
from nltk.tokenize import word_tokenize
from nltk.probability import FreqDist
from nltk.corpus import stopwords
import string, json

# Download NLTK tokenization resources (only needed once)
# nltk.download('punkt')
# nltk.download('stopwords')

# Define stopwords and punctuation
stop_words = set(stopwords.words('english'))
punctuation = set(string.punctuation)

# Sample text
text = "This is a sample text. This text is for counting word frequency using NLTK."

# Tokenize words
words = word_tokenize(text.lower())  # Convert to lowercase for case-insensitive counting

# Filter words
filtered_words = [word for word in words if word not in stop_words and word not in punctuation]

# Count word frequency
filtered_freq_dist = FreqDist(filtered_words)

wordfreqdict = dict(filtered_freq_dist)
print(wordfreqdict)

json_output = json.dumps(wordfreqdict)
print(json_output)

# Print word frequency
# for word, freq in filtered_freq_dist.items():
#     print(f"{word}: {freq}")
