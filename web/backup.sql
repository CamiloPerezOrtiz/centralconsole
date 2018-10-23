--
-- PostgreSQL database dump
--

-- Dumped from database version 10.5 (Ubuntu 10.5-0ubuntu0.18.04)
-- Dumped by pg_dump version 10.5 (Ubuntu 10.5-0ubuntu0.18.04)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: acl; Type: TABLE; Schema: public; Owner: camilo
--

CREATE TABLE public.acl (
    id integer NOT NULL,
    acl_id integer,
    disabled character varying(15) NOT NULL,
    name character varying(40) NOT NULL,
    client text NOT NULL,
    "time" text NOT NULL,
    targetrule text NOT NULL,
    allowip character varying(3) NOT NULL,
    redirectmode character varying(40) NOT NULL,
    redirect character varying(30) NOT NULL,
    safesearch character varying(3) NOT NULL,
    rewrite character varying(40) NOT NULL,
    rewritetime character varying(40) NOT NULL,
    description character varying(40) NOT NULL,
    namegroup character varying(25) NOT NULL
);


ALTER TABLE public.acl OWNER TO camilo;

--
-- Name: acl_id_seq; Type: SEQUENCE; Schema: public; Owner: camilo
--

CREATE SEQUENCE public.acl_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.acl_id_seq OWNER TO camilo;

--
-- Name: administrator; Type: TABLE; Schema: public; Owner: camilo
--

CREATE TABLE public.administrator (
    id integer NOT NULL,
    name character varying(25) NOT NULL,
    email character varying(50) NOT NULL,
    password character varying(255) NOT NULL,
    role character varying(15) NOT NULL,
    namegroup character varying(25) NOT NULL
);


ALTER TABLE public.administrator OWNER TO camilo;

--
-- Name: administrator_id_seq; Type: SEQUENCE; Schema: public; Owner: camilo
--

CREATE SEQUENCE public.administrator_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.administrator_id_seq OWNER TO camilo;

--
-- Name: log; Type: TABLE; Schema: public; Owner: camilo
--

CREATE TABLE public.log (
    id integer NOT NULL,
    description character varying(3) NOT NULL
);


ALTER TABLE public.log OWNER TO camilo;

--
-- Name: log_id_seq; Type: SEQUENCE; Schema: public; Owner: camilo
--

CREATE SEQUENCE public.log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.log_id_seq OWNER TO camilo;

--
-- Name: target; Type: TABLE; Schema: public; Owner: camilo
--

CREATE TABLE public.target (
    id integer NOT NULL,
    log_id integer,
    name character varying(255) NOT NULL,
    domainlist character varying(255) NOT NULL,
    urllist character varying(255) NOT NULL,
    regularexpression character varying(255) NOT NULL,
    redirect character varying(255) NOT NULL,
    description character varying(255) NOT NULL,
    namegroup character varying(25) NOT NULL,
    redirectmode character varying(25) NOT NULL
);


ALTER TABLE public.target OWNER TO camilo;

--
-- Name: target_id_seq; Type: SEQUENCE; Schema: public; Owner: camilo
--

CREATE SEQUENCE public.target_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.target_id_seq OWNER TO camilo;

--
-- Name: txtip; Type: TABLE; Schema: public; Owner: camilo
--

CREATE TABLE public.txtip (
    id integer NOT NULL,
    hostname character varying(40) NOT NULL,
    ip character varying(40) NOT NULL,
    cliente character varying(40) NOT NULL
);


ALTER TABLE public.txtip OWNER TO camilo;

--
-- Name: txtip_id_seq; Type: SEQUENCE; Schema: public; Owner: camilo
--

CREATE SEQUENCE public.txtip_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.txtip_id_seq OWNER TO camilo;

--
-- Data for Name: acl; Type: TABLE DATA; Schema: public; Owner: camilo
--

COPY public.acl (id, acl_id, disabled, name, client, "time", targetrule, allowip, redirectmode, redirect, safesearch, rewrite, rewritetime, description, namegroup) FROM stdin;
\.


--
-- Data for Name: administrator; Type: TABLE DATA; Schema: public; Owner: camilo
--

COPY public.administrator (id, name, email, password, role, namegroup) FROM stdin;
1	admin	admin@warriorslabs.com	$2y$04$VgGTt6I/t5P/p9KwE0wd4.4Z91XGnBp66jMsjgFu159h0rEZMuXHW	ROLE_SUPERUSER	Null
\.


--
-- Data for Name: log; Type: TABLE DATA; Schema: public; Owner: camilo
--

COPY public.log (id, description) FROM stdin;
\.


--
-- Data for Name: target; Type: TABLE DATA; Schema: public; Owner: camilo
--

COPY public.target (id, log_id, name, domainlist, urllist, regularexpression, redirect, description, namegroup, redirectmode) FROM stdin;
\.


--
-- Data for Name: txtip; Type: TABLE DATA; Schema: public; Owner: camilo
--

COPY public.txtip (id, hostname, ip, cliente) FROM stdin;
1	perez0	192.168.0.1	GrupoA
2	Perez1	192.168.0.2	GrupoB
3	perez2	192.168.0.3	GrupoC
4	perez3	192.168.0.4	GrupoD
5	perez4	192.168.0.5	GrupoA
6	Perez5	192.168.0.6	GrupoA
7	perez6	192.168.0.7	GrupoD
8	perez7	192.168.0.8	GrupoA
9	Perez8	192.168.0.9	GrupoA
10	Perez9	192.168.0.10	GrupoE
11	Perez10	192.168.0.11	GrupoF
12	Perez11	192.168.0.12	GrupoG
13	Perez12	192.168.0.13	GrupoH
\.


--
-- Name: acl_id_seq; Type: SEQUENCE SET; Schema: public; Owner: camilo
--

SELECT pg_catalog.setval('public.acl_id_seq', 1, false);


--
-- Name: administrator_id_seq; Type: SEQUENCE SET; Schema: public; Owner: camilo
--

SELECT pg_catalog.setval('public.administrator_id_seq', 1, true);


--
-- Name: log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: camilo
--

SELECT pg_catalog.setval('public.log_id_seq', 1, false);


--
-- Name: target_id_seq; Type: SEQUENCE SET; Schema: public; Owner: camilo
--

SELECT pg_catalog.setval('public.target_id_seq', 1, false);


--
-- Name: txtip_id_seq; Type: SEQUENCE SET; Schema: public; Owner: camilo
--

SELECT pg_catalog.setval('public.txtip_id_seq', 13, true);


--
-- Name: acl acl_pkey; Type: CONSTRAINT; Schema: public; Owner: camilo
--

ALTER TABLE ONLY public.acl
    ADD CONSTRAINT acl_pkey PRIMARY KEY (id);


--
-- Name: administrator administrator_pkey; Type: CONSTRAINT; Schema: public; Owner: camilo
--

ALTER TABLE ONLY public.administrator
    ADD CONSTRAINT administrator_pkey PRIMARY KEY (id);


--
-- Name: log log_pkey; Type: CONSTRAINT; Schema: public; Owner: camilo
--

ALTER TABLE ONLY public.log
    ADD CONSTRAINT log_pkey PRIMARY KEY (id);


--
-- Name: target target_pkey; Type: CONSTRAINT; Schema: public; Owner: camilo
--

ALTER TABLE ONLY public.target
    ADD CONSTRAINT target_pkey PRIMARY KEY (id);


--
-- Name: txtip txtip_pkey; Type: CONSTRAINT; Schema: public; Owner: camilo
--

ALTER TABLE ONLY public.txtip
    ADD CONSTRAINT txtip_pkey PRIMARY KEY (id);


--
-- Name: idx_466f2ffcea675d86; Type: INDEX; Schema: public; Owner: camilo
--

CREATE INDEX idx_466f2ffcea675d86 ON public.target USING btree (log_id);


--
-- Name: idx_bc806d1244082458; Type: INDEX; Schema: public; Owner: camilo
--

CREATE INDEX idx_bc806d1244082458 ON public.acl USING btree (acl_id);


--
-- Name: target fk_466f2ffcea675d86; Type: FK CONSTRAINT; Schema: public; Owner: camilo
--

ALTER TABLE ONLY public.target
    ADD CONSTRAINT fk_466f2ffcea675d86 FOREIGN KEY (log_id) REFERENCES public.log(id);


--
-- Name: acl fk_bc806d1244082458; Type: FK CONSTRAINT; Schema: public; Owner: camilo
--

ALTER TABLE ONLY public.acl
    ADD CONSTRAINT fk_bc806d1244082458 FOREIGN KEY (acl_id) REFERENCES public.log(id);


--
-- PostgreSQL database dump complete
--

