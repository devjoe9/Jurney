from nltk.tokenize import word_tokenize
from nltk.probability import FreqDist
from nltk.corpus import stopwords
import string, sys, json

# if len(sys.argv) > 1:
#     stop_words = set(stopwords.words('english'))
#     punctuation = set(string.punctuation)

#     text = sys.argv[1]
#     # text = "TESTING TESTING testing test lol"
#     words = word_tokenize(text.lower())

#     filtered_words = [word for word in words if word not in stop_words and word not in punctuation]
#     filtered_freq_dist = FreqDist(filtered_words)
#     word_freq_dict = dict(filtered_freq_dist)

#     # print(word_freq_dict)

#     json_output = json.dumps(word_freq_dict)
#     print(json_output)

if len(sys.argv) > 1:
    stop_words = set(stopwords.words('english'))
    punctuation = set(string.punctuation)

    text = sys.argv[1]
    words = word_tokenize(text.lower())

    filtered_words = [word for word in words if word not in stop_words and word not in punctuation]
    filtered_freq_dist = FreqDist(filtered_words)

    word_freq_dict = dict(filtered_freq_dist)
    sorted_word_freq_list = sorted(word_freq_dict.items(), key=lambda x: x[1], reverse=True)
    sorted_word_freq_dict = dict(sorted_word_freq_list)

    json_output = json.dumps(sorted_word_freq_dict)
    print(json_output)