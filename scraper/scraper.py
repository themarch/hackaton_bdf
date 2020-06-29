import gevent.monkey
gevent.monkey.patch_all()

from bs4 import BeautifulSoup
import re
import numpy
import pandas
import requests
import grequests
import urllib.parse
import mysql.connector

base_url = "https://ideas.repec.org"

def get_cursor(db):
    try:
        print('Connecting to the database :', db)
        conn = mysql.connector.connect(host='localhost',
                database=db,
                user='root',
                password='rootroot')
        print('Connection established to {}'.format(db))
    except Exception as e:
        raise(e)

    return conn, conn.cursor()

# --------- Parse author's informations
"""
"""
def get_email(lst):
    mail = ""
    for word in lst:
        if word == "m7i7":
            mail = mail[:-1] + "@"
        else:
            mail += word + "."
    return mail[:-1]


"""
"""
def personal_info_tr_dispatch(tr):
    for td in tr.find_all("td"):
        yield td.text

"""
"""
def personal_info_email_dispatch(tr):
    mail = ""
    email = tr.find('span')
    if email == None:
        return(tr.td['class'][0], None)
    mail = get_email(tr.span['data-liame2'][1:-1].replace('\"', "").split(",")[::-1])
    return (tr.td['class'][0], mail)

"""
"""
def personal_info_homepage_dispatch(tr):
    try:
        link = tr.a['href']
    except TypeError:
        link = None
    return (tr.td['class'][0], None)

"""
"""
def personal_info_address_dispatch(tr):
    address = None
    for td in tr.children:
        if td.string == None:
            continue
        else:
            new_string = td.string.strip()
            if new_string == "":
                continue
            address = td.string
            break
    return (tr.td['class'][0], address)

"""
"""
def personal_info_phone_dispatch(tr):
    phone_nbr = None
    for td in tr.children:
        if td.string == None:
            continue
        else:
            new_string = td.string.strip()
            if new_string == "":
                continue
            phone_nbr = td.string
            break
    return (tr.td['class'][0], phone_nbr)

"""
Get all of the author's personal infos
"""
def scrap_author_personal_info(soup):
    if soup == None:
        return ()
    #url_info = url + "#person"
    #author_source = requests.get(url_info).text
    #author_soup = BeautifulSoup(author_source, 'lxml')
    #info_table = author_soup.find('table')
    info_table = soup.find('table')
    body = info_table.tbody
    infos = dict()
    for tr in body.find_all("tr"):
        if tr.find("td", {"class" : "emaillabel"}):
            (key, value) = personal_info_email_dispatch(tr)
        elif tr.find("td", {"class" : "homelabel"}):
            (key, value) = personal_info_homepage_dispatch(tr)
        elif tr.find("td", {"class" : "postallabel"}):
            (key, value) = personal_info_address_dispatch(tr)
        elif tr.find("td", {"class" : "phonelabel"}):
            (key, value) = personal_info_phone_dispatch(tr)
        else:
            try:
                (key, value) = personal_info_tr_dispatch(tr)
            except ValueError:
                key = None

        if key != None:
            infos[key] = value
#    print(infos)
    return infos



"""
Get all of the author's university links
"""
def scrap_author_affiliation(soup):
    if soup == None:
        return ()
    for span in soup.find_all("span", {"class" : "handlelabel"}):
        if span.a == None:
            for a in soup.find_all("a", recursive=False):
                yield a['href']
            break
        yield span.a['href']


"""
Get all of the author's articles links
"""
def scrap_author_research(soup):
    if soup == None:
        return ()
    #url_papers = url + "#research"
    #papers_source = requests.get(url_papers).text
    #papers_soup = BeautifulSoup(papers_source, 'lxml')
    #research = papers_soup.find("div", {"id" : "research"})
    for ol in soup.find_all("ol", {"class": "list-group"}):
        for li in ol.findChildren("li", recursive=False):
            yield urllib.parse.urljoin(base_url, li.a['href'])


"""
"""
def personal_info_to_formated(infos, lst_index_uni):
    cols = ["First Name:", "Middle Name:", "Last Name:", "Suffix:", "RePEc Short-ID:", "emaillabel", "homelabel", "postallabel", "phonelabel", "Twitter", "Terminal Degree"]
    ret_infos = []
    for key in cols:
        try:
            value = infos[key]
            if value == "":
                value = None
        except KeyError:
            value = None
        ret_infos.append(value)

    for i in range(4):
        if i >= len(lst_index_uni):
            ret_infos.append(None)
        else:
            ret_infos.append(str(lst_index_uni[i]))
    if ret_infos[0] == None:
        first_name = ""
    else:
        first_name = ret_infos[0] + " "
    if ret_infos[2] == None:
        last_name = ""
    else:
        last_name = ret_infos[2] + " "
    all_name = (first_name + last_name).strip()
    all_name_invers = (last_name + first_name).strip()
    return (ret_infos[:3] + [all_name, all_name_invers] + ret_infos[3:])


"""
"""
def scrap_author_page(urls_uni, count_uni, soup):
    info_author_soup = soup.find("div", {"id": "person"})
    infos = scrap_author_personal_info(info_author_soup)

    affiliation_author_soup = soup.find("div", {"id": "affiliation"})
    gen_affiliation = scrap_author_affiliation(affiliation_author_soup)
    indexes_uni = []
    for url in gen_affiliation:
        try:
            a = urls_uni[str(url)]
            indexes_uni.append(urls_uni[url])
        except KeyError:
            indexes_uni.append(count_uni)
            urls_uni[url] = count_uni
            count_uni += 1
    info_db_format = personal_info_to_formated(infos, indexes_uni)

    research_author_soup = soup.find("div", {"id": "research"})
    return (urls_uni, count_uni, info_db_format, scrap_author_research(research_author_soup))



"""
"""
def scrap_uni_contact(soup):
    body = soup.find("div", {"id" : "details"})
    title = soup.find("div", {"id" : "title"})
    name_uni = title.find_all("h2")
    name = ""
    for part_name in name_uni:
        name += part_name.text + " "
    info = [name.strip()]
    for line in body.text.split("\n"):
        if line == "" or ":" not in line:
            continue
        info.append(line.split(":", 1)[1].strip())
    return (info)

"""
"""
def scrap_paper_page(url, index, soup):
    name = soup.find("div", {"id": "title"})
    if name == None:
        name_article = None
    else:
        name_article = name.h1.text
    classification = soup.find("div", {"id" : "more"})
    if classification == None:
        return None
    header_jel = None
    for header in classification.find_all("h3"):
        if "JEL" in header.text:
            jel_class = header.next_sibling.next_sibling
            break
    else:
        return None
    lst_class = []
    for li in jel_class.find_all("li"):
        try:
            (title, denominations) = [word.strip() for word in li.text.split('-', 1)]
        except:
            title = li.text.strip()
            denominations = None
        if denominations is None:
            main = None
            second = None
            third = None

        try:
            (main, left) = [word.strip() for word in denominations.split('- -', 1)]
        except:
            main = denominations
            left = None
        if left is None:
            second = None
            third = None

        try:
            (second, third) = [word.strip() for word in left.split('- - -', 1)]
        except:
            second = left
            third = None
        lst_class.append([url, name_article, index, title, main, second, third])
    return lst_class

def scrap_papers_page(papers_url):
    all_papers = []
    for (i, papers) in enumerate(papers_url):
        reqs_papers = (grequests.get(link) for link in papers)
        resp_papers = grequests.imap(reqs_papers, grequests.Pool(20))
        for r in resp_papers:
            try:
                soup = BeautifulSoup(r.text, 'lxml')
                infos_paper = scrap_paper_page(r.url, str(i+1), soup)
                if infos_paper == None:
                    continue
                for info_paper in infos_paper:
                    all_papers.append(info_paper)
                #print("infos: ", info_paper)
            except Exception as e:
                print("Unexpected output : {}".format(e), file=sys.stderr)
        #TODO -> populate db with papers_info
    return all_papers

# ------- Functions to populate into database ----------

# Populate the USER table
"""
"""
def populate_user_data(conn, cursor, rows):
    cols_user_data_name = "`link_user`, `prenom_user`, `surnom_user`, `nom_user`, `all_name`, `all_name_invers`, `suffix_user`, `repec_short-id`, `email_user`, `homepage_user`, `adresse_user`, `telephone_user`, `twitter_user`, `degree_user`, `id_etablissement_user1`, `id_etablissement_user2`, `id_etablissement_user3`, `id_etablissement_user4`"
    values_string = '%s, ' * len(cols_user_data_name)
 
    query = f"""INSERT INTO USER ({cols_user_data_name} VALUES ({values_string}))"""
    try:
        cursor.executemany(query, rows)
    except Exception as e: 
        print(rows)
        print(query)
        sys.exit("Unexpected output : {}".format(e))
    conn.commit()

# Populate the ETABLISSEMENT table
"""
"""
def populate_uni_data(conn, cursor, rows):
    cols_uni_data_name ="`nom_etablissement`, `pays-ville_etablissement`,`site_etablissement`, `email_etablissement`,`phone_etablissement`,`fax_etablissement`,`adresse_etablissement`, `function_etablissement`"
    values_string = '%s, ' * len(cols_uni_data_name)
 
    query = f"""INSERT INTO ETABLISSEMENT ({cols_uni_data_name} VALUES ({values_string}))"""
    try:
        cursor.executemany(query, rows)
    except Exception as e: 
        print(rows)
        print(query)
        sys.exit("Unexpected output : {}".format(e))
    conn.commit()

# Populate the ARTICLE table
"""
"""
def populate_papers_data(conn, cursor, rows):
    cols_papers_data_name ="`link_paper`, `name_paper`,`id_auteur`, `JEL_name`,`JEL_1`,`JEL_2`,`JEL_3`,`JEL_4`"
    values_string = '%s, ' * len(cols_papers_data_name)
 
    query = f"""INSERT INTO ARTICLE ({cols_uni_data_name} VALUES ({values_string}))"""
    try:
        cursor.executemany(query, rows)
    except Exception as e: 
        print(rows)
        print(query)
        sys.exit("Unexpected output : {}".format(e))
    conn.commit()

"""
"""
def scrap_unis_page(lst_url):
    infos_uni = []
    reqs = (grequests.get(link) for link in lst_url)
    resp = grequests.imap(reqs, grequests.Pool(20))
    for r in resp:
        try:
            soup = BeautifulSoup(r.text, 'lxml')
            info = scrap_uni_contact(soup)
            info = [lst_url.index(r.url[:4] + r.url[5:]), r.url] + info[:7] + info[8:]
            infos_uni.append(info)
        except Exception as e:
            print("Unexpected output : {}".format(e), file=sys.stderr)
    infos_uni.sort()
    infos_uni = [info[1:] for info in infos_uni]
    return(infos_uni)

"""
"""
def export_csv(authors, uni, papers):
    cols_user_data = ["link_user", "prenom_user", "surnom_user", "nom_user", "all_name", "all_name_invers", "suffix_user", "repec_short-id", "email_user", "homepage_user", "adresse_user", "telephone_user", "twitter_user", "degree_user", "id_etablissement_user1", "id_etablissement_user2", "id_etablissement_user3", "id_etablissement_user4"]
    data_authors = numpy.array(authors)
    df = pandas.DataFrame(data_authors, columns=cols_user_data)
    df.to_csv("authors.csv", index=False)

    cols_uni_data = ["link_etablissement", "nom_etablissement", "pays-ville_etablissement","site_etablissement", "email_etablissement","phone_etablissement","fax_etablissement","adresse_etablissement", "function_etablissement"]
    data_uni = numpy.array(uni)
    df = pandas.DataFrame(data_uni, columns=cols_uni_data)
    df.to_csv("uni.csv", index=False)
    
    cols_papers_data = ["link_paper", "name_paper", "id_auteur", "JEL_1", "JEL_2","JEL_3", "JEL_4"]
    data_papers = numpy.array(papers)
    df = pandas.DataFrame(data_papers, columns=cols_papers_data)
    df.to_csv("papers.csv", index=False)

"""
This function should go throught all the names gathered in `ideas.repec.org`.
It looks for their homepage, their names, and the number of articles written
"""
def parse_repec_author():
    url_repec = "https://ideas.repec.org/i/eall.html"

    source = requests.get(url_repec)
    soup = BeautifulSoup(source.text, 'lxml')

    url_uni = dict()
    count_uni = 1

    print("\n---Scrapping author's links----\n")
    #Gather all of the authors' main page url
    urls_author = []
    for (i,table) in enumerate(soup.find_all('table')):
        if table == None or i < 3:
            continue
        for (j,td) in enumerate(table.find_all('td')):
            if td.a == None:
                continue
            url = td.a['href']
            urls_author.append(base_url + url)

    print("\n---Scrapping authors contact----\n")

    #Query all of the author's pages. Gather their infos, links to unis' pages
    #and links to author's papers
    info_authors = []
    papers_url = []
    reqs_authors = (grequests.get(link) for link in urls_author[:10])
    resp_authors = grequests.imap(reqs_authors , grequests.Pool(20))
    for r in resp_authors:
        try:
            soup = BeautifulSoup(r.text, 'lxml')
            (url_uni, count_uni, info_author, urls_papers) = scrap_author_page(url_uni, count_uni, soup)
            info = [r.url] + info_author
            info_authors.append(info)
            papers_url.append(urls_papers)
            if len(papers_url) > 50000:
                #populate_papers_data(conn, cursor, pa
                continue
        except Exception as e:
            print("Unexpected output : {}".format(e), file=sys.stderr)

    #print(info_authors)
    #TODO -> populate db with author_info
    #populate_user_data(conn, cursor, infos_authors):

    print("\n---Scrapping unis contact----\n")

    #TODO -> populate db with uni_info
    info_unis = scrap_unis_page([url for url in url_uni.keys()])
    #populate_user_data(conn, cursor, infos_uni):


    print("\n---Scrapping papers infos----\n")
    all_papers = scrap_papers_page(papers_url)
    
    export_csv(info_authors, info_unis, all_papers)


if __name__ == "__main__":
    #string = "D31 - Microeconomics - - Distribution"
    #print([title, main, second, third])
    #print(string.split('-', 1))
    #jel_infos = re.findall(r'[A-Z]\d*(?: - )[\w\s]* - - [\w\s]* - - - [\w\s]*', string)
    #print(jel_infos)
    #url_uni = {"https://edirc.repec.org/data/edlseuk.html": 0, "https://edirc.repec.org/data/mccbede.html": 1}
    #count_uni = 2
    #url_test = "https://ideas.repec.org/e/pab7.html"
    #source = requests.get(url_test)
    #soup = BeautifulSoup(source.text, 'lxml')
    #(url_uni, count_uni, info_author, urls_papers) = scrap_author_page(url_uni, count_uni, soup)
    #urls_papers = [link for link in urls_papers]
    #info_unis = scrap_unis_page([url for url in url_uni.keys()])
    #export_csv([[url_test] + info_author], info_unis)
    #url_paper = "https://ideas.repec.org/b/elg/eebook/15474.html"
    #url_paper = "https://ideas.repec.org/p/imf/imfwpa/07-48.html"
    #source = requests.get(url_paper)
    #soup = BeautifulSoup(source.text, 'lxml')
    #scrap_paper_page(url_paper, 0, soup)
    #urls_papers = ["https://ideas.repec.org/p/gwi/wpaper/2018-10.html", "https://ideas.repec.org/p/gwi/wpaper/2017-15.html", "https://ideas.repec.org/p/gwi/wpaper/2017-2.html"]
    #reqs_papers = (grequests.get(link) for link in urls_papers)
    #resp=grequests.imap(reqs_papers, grequests.Pool(20))
    #for (i, r) in enumerate(resp):
    #    try:
    #        soup = BeautifulSoup(r.text, 'lxml')
    #        ret = scrap_paper_page(soup)
    #        print(ret)
    #    except Exception as e:
    #        print("Unexpected output : {}".format(e), file=sys.stderr)
    p
